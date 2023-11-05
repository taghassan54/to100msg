<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reply;
use App\Models\Template;
use App\Models\Device;
use Auth;
use DB;
class ChatbotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(request()->has('deviceId')){

            $devices = Device::where('user_id',Auth::id())->where('uuid',request()->deviceId)->where('status',1)->latest()->first();

            if(!$devices){abort(404);}

            $templates=Template::where('user_id',Auth::id())->where('status',1)->latest()->get();
            $replies=Reply::where('user_id',Auth::id()) ->where('device_id',$devices->id)->latest()->paginate(20);
            $total_replies=Reply::where('user_id',Auth::id())->where('device_id',$devices->id)->count();
            $template_replies=Reply::where('user_id',Auth::id())->where('device_id',$devices->id)->where('template_id','!=',null)->count();
            $text_replies=Reply::where('user_id',Auth::id())->where('device_id',$devices->id)->where('template_id',null)->count();
            return response()->json([
                'chatbot'=>[
                    'device'=>$devices,
                    'templates'=>$templates,
                    'replies'=>$replies,
                    'total_replies'=>$total_replies,
                    'template_replies'=>$template_replies,
                    'text_replies'=>$text_replies,
                    'match_types'=>['equal','like'],
                    'reply_types'=>['template','text','file'],
                ]
            ],200);

        }else{
            return response()->json([
                'message'=>__('Chatbot device Id required')
            ],404);
            // $devices = Device::where('user_id',Auth::id())->where('status',1)->latest()->get();
            // return response()->json([
            //     'devices'=>$devices
            // ],200);
        }

    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (getUserPlanData('chatbot') == false) {
            return response()->json([
                'message'=>__('Chatbot features is not available with your subscription')
            ],401);
        }
        $validated = $request->validate([
            'keyword' => 'required|max:100',
            'match_type' => 'required',
            'reply_type' => 'required',
            'device' => 'required'
        ]);

        $device = Device::where('user_id',Auth::id())->where('uuid',$request->device)->first();

        if(empty($device))
        return response()->json([
            'message'  => __('Device Not Found'),
          ],404);

        if ($request->reply_type == 'template') {
             $validated = $request->validate([
                'template' => 'required',
            ]);
            $template=Template::where('user_id',Auth::id())->where('status',1)->findorFail($request->template);
        }
        else{
            $validated = $request->validate([
                'reply' => 'required|max:1000',
            ]);
        }

        $replies=Reply::where('device_id',$device->id)->where('keyword',$request->keyword)->latest()->count();

if($replies > 0)
return response()->json([
    'message'  => __('Reply Key Exist'),
  ],409);

        $reply = new Reply;
        $reply->user_id = Auth::id();
        $reply->device_id =$device->id;
        $reply->template_id = $request->reply_type == 'template' ? $template->id : null;
        $reply->keyword = $request->keyword;
        $reply->reply = $request->reply_type != 'template' ? $request->reply : null;
        $reply->match_type= $request->match_type == 'equal' ? 'equal' : 'like';
        $reply->reply_type= $request->reply_type == 'template' ? 'template' : ($request->reply_type == 'file' ?'file': 'text');
        $reply->save();

       return response()->json([
                'message'  => __('Reply Created Successfully'),
                'redirect' => route('user.chatbot.index',[
                    'deviceId'=>$device->uuid
                ]),
                'reply'=>$reply
            ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $validated = $request->validate([
            'keyword' => 'required|max:100',
            'match_type' => 'required',
            'reply_type' => 'required',
            'device' => 'required'
        ]);

        // $device = Device::where('user_id',Auth::id())->findorFail($request->device);
        $device = Device::where('user_id',Auth::id())->where('uuid',$request->device)->first();

        if ($request->reply_type == 'template') {
             $validated = $request->validate([
                'template' => 'required',
            ]);
            $template=Template::where('user_id',Auth::id())->where('status',1)->findorFail($request->template);
        }else if($request->reply_type=='file'){
            $validated = $request->validate([
                'file' => 'required',
                'reply' => 'required|max:1000',
            ]);
        }
        else{
            $validated = $request->validate([
                'reply' => 'required|max:1000',
            ]);
        }

        $reply =  Reply::where('user_id',Auth::id())->findorFail($id);
        $reply->user_id = Auth::id();
        $reply->device_id =$device->id;
        $reply->template_id = $request->reply_type == 'template' ? $template->id : null;
        $reply->keyword = $request->keyword;
        $reply->reply = $request->reply_type != 'template' ? $request->reply : null;
        $reply->match_type= $request->match_type == 'equal' ? 'equal' : 'like';
        // $reply->reply_type= $request->reply_type == 'template' ? 'template' : 'text';
        $reply->reply_type= $request->reply_type == 'template' ? 'template' : ($request->reply_type == 'file' ?'file': 'text');
        $reply->save();

        return response()->json([
                'message'  => __('Reply Created Successfully'),
                'redirect' => route('user.chatbot.index',[
                    'deviceId'=>$device->uuid
                ]),
                'reply'=>$reply
            ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reply =  Reply::where('user_id',Auth::id())->findorFail($id);
        $reply->delete();

        return response()->json([
            'message' => __('Congratulations! Your Reply Successfully Removed'),
            'redirect' => route('user.chatbot.index')
        ]);
    }
}
