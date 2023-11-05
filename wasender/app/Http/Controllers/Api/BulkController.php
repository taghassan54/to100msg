<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Smstransaction;
use App\Models\Smstesttransactions;
use App\Http\Requests\Bulkrequest;
use App\Models\User;
use App\Models\App;
use App\Models\Device;
use App\Models\Contact;
use App\Models\Template;
use App\Models\Reply;
use App\Models\Webhook;
use Carbon\Carbon;
use App\Traits\Whatsapp;
use Http;
use Auth;
use Str;
use DB;
use Session;
use GuzzleHttp\Client;


class BulkController extends Controller
{
    use Whatsapp;



    public function smsTransactions() {
        $logs=Smstransaction::where('user_id',Auth::id())
        ->with('device','app','template')
        ->latest()
        ->paginate(30);
  $total_messages=Smstransaction::where('user_id',Auth::id())->count();
  $today_messages=Smstransaction::where('user_id',Auth::id())
                  ->whereRaw('date(created_at) = ?', [Carbon::now()->format('Y-m-d')] )
                  ->count();
  $last30_messages=Smstransaction::where('user_id',Auth::id())
                      ->where('created_at', '>', now()
                      ->subDays(30)
                      ->endOfDay())
                      ->count();

                      return response()->json(['logs'=>$logs,'total_messages'=>$total_messages,'today_messages'=>$today_messages,'last30_messages'=>$last30_messages]);
    }
    public function phoneCodes() {
        $phoneCodes=file_exists('uploads/phonecode.json') ? json_decode(file_get_contents('uploads/phonecode.json')) : [];
        return response()->json(['phoneCodes'=>$phoneCodes],200);
    }



    /**
     * sent message
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submitRequest(Bulkrequest $request)
    {

       // dd($request->all());
        $user=User::where('status',1)->where('will_expire','>',now())->where('authkey',$request->authkey)->first();
        $app=App::where('key',$request->appkey)->whereHas('device')->with('device')->where('status',1)->first();

        if ($user == null || $app == null) {
            return response()->json(['error'=>'Invalid Auth and AppKey'],401);
        }

        if (getUserPlanData('messages_limit', $user->id) == false) {
            return response()->json([
                'message'=>__('Maximum Monthly Messages Limit Exceeded')
            ],401);
        }

        if (!empty($request->template_id)) {

            $template = Template::where('user_id',$user->id)->where('uuid',$request->template_id)->where('status',1)->first();
            if (empty($template)) {
                return response()->json(['error'=>'Template Not Found'],401);
            }

            if (isset($template->body['text'])) {
                $body = $template->body;
                $text=$this->formatText($template->body['text'],[],$user);
                $text=$this->formatCustomText($text,$request->variables ?? []);
                $body['text'] = $text;
            }
            else{
                $body=$template->body;
            }
            $type = $template->type;


        }
        else{

            $text=$this->formatText($request->message);
            if(!empty($request->file)){


                    $explode=explode('.', $request->file);
                    $file_type=strtolower(end($explode));
                    $extentions=[
                        'jpg'=>'image',
                        'jpeg'=>'image',
                        'png'=>'image',
                        'webp'=>'image',
                        'pdf'=>'document',
                        'docx'=>'document',
                        'xlsx'=>'document',
                        'csv'=>'document',
                        'txt'=>'document',
                        'mp3'=>'audio',
                        'aac'=>'audio',
                        'ogg'=>'audio',
                        'mp4'=>'video',
                        '3gp'=>'video',
                        'mov'=>'video'
                    ];

                    if(!isset($extentions[$file_type])){
                        $validators['error'] = 'file type should be jpg,jpeg,png,webp,pdf,docx,xlsx,csv,txt';
                        return response()->json($validators,403);
                    }


                $body[$extentions[$file_type]]=['url' => $request->file];
                $body['caption'] = $text;
                $type='text-with-media';
            }else if(!empty($request->degreesLatitude)&&!empty($request->degreesLongitude)){


                $body['degreesLatitude'] = $request->degreesLatitude;
                $body['degreesLongitude'] = $request->degreesLongitude;
                $body['text'] = $text;
                $type='text-with-location';
            }
            else{
                $body['text'] = $text;
                $type='plain-text';
            }

        }

        if (!isset($body)) {
            return response()->json(['error'=>'Request Failed'],401);
        }

        try {

if($type=='text-with-location'){
    $response= $this->messageSend($body,$app->device_id,$request->to,$type);
}else{
    //  dd($body,$app->device_id,$request->to,$type,true);
    $response= $this->messageSend($body,$app->device_id,$request->to,$type,true);
}

// return  $this->chat($text,$request->from,$app->device_id);

            if($response==false){

                $sessions=Http::get(env('WA_SERVER_URL').'/sessions/status/device_'.$app->device_id);
                $message= $sessions->status() == 200 ? __('Device Connected Successfully') : "Device is Not Connected!";
                return response()->json(['error'=>'Request Failed','session'=>['message'=>$message,'connected'=> $sessions->status() == 200 ? true : false]],401);
            }

            if ($response['status'] == 200) {

                $logs['user_id']=$user->id;
                $logs['device_id']=$app->device_id;
                $logs['app_id']=$app->id;
                $logs['from']=$app->device->phone ?? null;
                $logs['to']=$request->to;
                $logs['template_id']=$template->id ?? null;
                $logs['type']='from_api';

                $this->saveLog($logs);

                return response()->json(['message_status'=>'Success','data'=>[
                    'from'=>$app->device->phone ?? null,
                    'to'=>$request->to,
                    'status_code'=>200,
                ]],200);
            }
            else{
                return response()->json(['error'=>'Request Failed','res'=>$response],401);

            }

        } catch (Exception $e) {

         return response()->json(['error'=>'Request Failed','res'=>$response],401);
     }

 }


 /**
  * set status device
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function setStatus($device_id,$status){

       $device_id=str_replace('device_','',$device_id);

       $device=Device::where('id',$device_id)->first();
       if (!empty($device)) {
          $device->status=$status;
          $device->save();
       }


  }


  public function sendGroupMessage(Request $request, $id)
  {
      $device = Device::where("user_id", Auth::id())
          ->where("status", 1)
          ->where("uuid", $id)
          ->first();
      abort_if(empty($device), 404);

      $validated = $request->validate([
          "group" => "required|max:50",
          "group_name" => "required|max:100",
          "selecttype" => "required",
      ]);

      $isGroup = explode("@", $request->group);
      $isGroup = $isGroup[1];
      abort_if($isGroup != "g.us", 404);

      if ($request->selecttype == "template") {
          $validated = $request->validate([
              "template" => "required",
          ]);

          $template = Template::where("user_id", Auth::id())
              ->where("status", 1)
              ->findorFail($request->template);

          if (isset($template->body["text"])) {
              $body = $template->body;
              $user = User::where("id", Auth::id())->first();

              $text = $this->formatText($template->body["text"], [], $user);
              $body["text"] = $text;
          } else {
              $body = $template->body;
          }
          $type = $template->type;
      } else {
          $validated = $request->validate([
              "message" => "required|max: 500",
          ]);

          $text = $this->formatText($request->message);
          $body["text"] = $text;
          $type = "plain-text";
      }

      if (!isset($body)) {
          return response()->json(["error" => "Request Failed"], 401);
      }

      try {
          $response = $this->sendMessageToGroup(
              $body,
              $device->id,
              $request->group,
              $type,
              true,
              0
          );

          if ($response["status"] == 200) {
              $logs["user_id"] = Auth::id();
              $logs["device_id"] = $device->id;
              $logs["from"] = $device->phone ?? null;
              $logs["to"] = "Group : " . $request->group_name;
              $logs["template_id"] = $template->id ?? null;
              $logs["type"] = "single-send";
              $this->saveLog($logs);

                              return response()->json(['message_status'=>'Success','data'=>[
                    'from'=>$device->phone ?? null,
                    'to'=>"Group : " . $request->group_name,
                    'status_code'=>200,
                ]],200);

          } else {
              return response()->json(["error" => "Request Failed"], 401);
          }
      } catch (Exception $e) {
          return response()->json(["error" => "Request Failed"], 401);
      }
  }

  /**
  * receive webhook response
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function webHook(Request $request,$device_id){
       $session=$device_id;
       $device_id=str_replace('device_','',$device_id);

       $device=Device::with('user')->whereHas('user',function($query){
        return $query->where('will_expire','>',now());
       })->where('id',$device_id)->first();

       if(!$device)
       return;

       $request_from=explode('@',$request->from);
       $request_from=$request_from[0];

       $message_id=$request->message_id ?? null;
       $message=json_encode($request->message ?? '');
       $message=json_decode($message);

       $imageMessage=null;
       $type="chat";

      // $device_id=$device_id;

       if (isset($message->conversation)) {
            $message = $message->conversation ?? null;
       }
       elseif (isset($message->extendedTextMessage)) {
        $extendedTextMessage=$message->extendedTextMessage;
           $message = $message->extendedTextMessage->text ?? null;
           $type="extendedText";
       }
       elseif (isset($message->buttonsResponseMessage)) {
           $message = $message->buttonsResponseMessage->selectedDisplayText ?? null;
       }
       elseif (isset($message->listResponseMessage)) {
           $message = $message->listResponseMessage->title ?? null;
       }
       elseif (isset($message->locationMessage)) {
            $locationMessage=[
                "degreesLatitude"=>$message->locationMessage->degreesLatitude??'',
                "degreesLongitude"=>$message->locationMessage->degreesLongitude??'',
                "jpegThumbnail"=>$message->locationMessage->jpegThumbnail??'',
            ];
            $type="location";
            // $message = null;
       }
       elseif (isset($message->contactsArrayMessage)) {
        $contactsArrayMessage=$message->contactsArrayMessage??null;
        $type="contactsArray";
// $message = null;
       }
       elseif (isset($message->reactionMessage)) {
$reactionMessage=$message->reactionMessage??null;
$messageContextInfo=$message->messageContextInfo??null;
$reaction=[
'reactionMessage'=>$reactionMessage,
'messageContextInfo'=>$messageContextInfo,
];
$type="reaction";
// $message = null;
       }
       elseif (isset($message->contactMessage)) {
$contactMessage=$message->contactMessage??null;

$type="contact";
// $message = null;
       }
       elseif (isset($message->audioMessage)) {

$type="audio";

       }
       elseif (isset($message->videoMessage)) {

$type="video";

       }
       elseif (isset($message->documentMessage)) {
$type="document";
       }
       elseif (isset($message->imageMessage)) {
        // $message = $message->imageMessage->caption ?? null;
        $type="image";
    }
       elseif (isset($message->stickerMessage)) {
        $type="sticker";
    }
       elseif (isset($message->pollCreationMessage)) {
        $pollCreationMessage = $message->pollCreationMessage ?? null;
        // $message =  null;

        $type="pollCreation";
    }
       elseif (isset($message->pollUpdateMessage)) {
        $pollUpdateMessage = $message->pollUpdateMessage ?? null;
        // $message =  null;

        $type="pollUpdate";
    }
       elseif (isset($message->protocolMessage)) {
        $protocolMessage = $message->protocolMessage ?? null;
        // $message =  null;

        $type="protocol";
    }
       else{
        // $message = null;
       }

       if($device->webhook_url)
{
       try {



    $webhookData=[
        "event_type" => $request->fromMe?"message_sent":"message_received",
        "instanceId" => $device->uuid,
        "data" => [

          "id" => $request->from."_".$request->message_id,
          "message_id"=>$request->message_id,
          "from" => $request_from,
          "to" =>$device->phone??'',
          "type" => $type,
          "body" => $message,
        //   "locationMessage"=>$locationMessage??null,
        //   "contactMessage"=>$contactMessage??null,
        //   "contactsArrayMessage"=>$contactsArrayMessage??null,
        //   "reactionMessage"=>$reaction??null,
        //   "pollCreationMessage"=>$pollCreationMessage??null,
        //   "pollUpdateMessage"=>$pollUpdateMessage??null,
        //   "extendedTextMessage"=>$extendedTextMessage??null,
        //   "protocolMessage"=>$protocolMessage??null,
          "media" => $request->media??null,
          "fromMe" => $request->fromMe,
          "isForwarded" => false,
          "time" => $request->messageTimestamp,
          //'all'=>$request->json()->all(),

        ]
        ];
           //https://0to100.store/webhook.php
           $response = Http::post($device->webhook_url,
           $webhookData
        );

        $hook = new Webhook;
        $hook->device_id = $device->id;
        $hook->user_id = $device->user_id;
        // $hook->payload = json_encode($request->message ?? '');
        $hook->payload = json_encode($webhookData ?? '');
        $hook->hook = $device->hook_url;
        $hook->save();




} catch (\Exception $e) {
    //throw $th;
    return response()->json([
        'message'  => ["text"=> 'An error occurred: ' . $e->getMessage()],
        'receiver' =>"971569501867",
        'session_id' => $session
    ],200);
   }

}else{


       info($message);

       if($request->fromMe){
        return response()->json([
            'message'  => array('text' => null),
            'receiver' => $request->from,
            'session_id' => $session,
            'reason'=>"fromMe"
          ],403);
       }
    //    dd($device,$message,$device->bot_status);
       if ($device != null && $message != null && $device->bot_status=='1') {
          $replies=Reply::where('device_id',$device_id)->with('template')->where('keyword','LIKE','%'.$message.'%')->latest()->get();

          if($message=="#")
          {

            try {
                return response()->json($this->returnWelcomeResponse($request->from,$session,$device->welcomeMessage),200) ;

            } catch (\Exception $e) {
                return response()->json([
                    'message'  => ["text"=> 'An error occurred: ' . $e->getMessage()],
                    'receiver' =>"971569501867",
                    'session_id' => $session
                ],200);
            }

          }

          if($replies->count()==0){

        //   return  $this->chat($message,$request->from,$session,$device->id);



          $lastLogRow =  Smstransaction::where('to',$request_from)->latest()->first();

          if ($lastLogRow) {

            $createdAt = Carbon::parse($lastLogRow->messageTimestamp)->shiftTimezone('Asia/Dubai');
            $tenMinutesAgo = Carbon::parse($request->messageTimestamp)->shiftTimezone('Asia/Dubai')->subMinutes((int)$device->resendToMain??5);

            $logs['user_id']=$device->user_id;
            $logs['device_id']=$device->id;
            $logs['from']=$device->phone ?? null;
            $logs['to']=$request_from;
            $logs['type']='chatbot';
            $logs['messageTimestamp']=$request->messageTimestamp??'';
            $this->saveLog($logs);

            if ($createdAt->lt($tenMinutesAgo)) {
                if($device->welcomeMessageEnable==1)
                return response()->json($this->returnWelcomeResponse($request->from,$session,$device->welcomeMessage),200) ;
            } else {
                if($device->errorMessageEnable==1)
                return response()->json([
                    'message'  => ["text"=> $device->errorMessage??"I don't know this command. To get commands list - return to the menu (#) "],
                    'receiver' => $request->from,
                    'session_id' => $session
                ],200);
            }

        } else {

            if($device->welcomeMessageEnable==1)
                return response()->json($this->returnWelcomeResponse($request->from,$session,$device->welcomeMessage),200);

        }
        }



          foreach ($replies as $key => $reply) {
            if ($reply->match_type == 'equal') {

                if ($reply->reply_type == 'text') {

                  $logs['user_id']=$device->user_id;
                  $logs['device_id']=$device->id;
                  $logs['from']=$device->phone ?? null;
                  $logs['to']=$request_from;
                  $logs['type']='chatbot';
                  $logs['messageTimestamp']=$request->messageTimestamp??'';
                  $this->saveLog($logs);

                 return response()->json([
                    'message'  => array('text' => $reply->reply),
                    'receiver' => $request->from,
                    'session_id' => $session
                  ],200);


                }
                else if($reply->reply_type =="template"){
                    if (!empty($reply->template)) {
                        $template = $reply->template;

                        if (isset($template->body['text'])) {
                            $body = $template->body;
                            $text=$this->formatText($template->body['text'],[],$device->user);
                            $body['text'] = $text;

                        }
                        else{
                            $body=$template->body;
                        }

                        $logs['user_id']=$device->user_id;
                        $logs['device_id']=$device->id;
                        $logs['from']=$device->phone ?? null;
                        $logs['to']=$request_from;
                        $logs['type']='chatbot';
                        $logs['template_id']=$template->id ?? null;
                        $logs['messageTimestamp']=$request->messageTimestamp??'';
                        $this->saveLog($logs);

                        return response()->json([
                            'message'  => $body,
                            'receiver' => $request->from,
                            'session_id' => $session
                        ],200);
                    }
                }else{

                }
                break;
            }
          }
       }
    }
       return response()->json([
            'message'  => array('text' => null),
            'receiver' => $request->from,
            'session_id' => $session,
            'reason'=>"no replay"
          ],403);

    }

    public function returnWelcomeResponse($from,$session,$message)  {
      return [
        'message'  => ["text"=>"".$message],
        'receiver' => $from,
        'session_id' => $session
    ];
    }


    public function chat($user_message,$from,$session,$deviceId)
    {

        $body['text'] = "إنتظر قليلا انا افكر في رسالتك \" ".$user_message." \" ... ";
        $type='plain-text';

        $response= $this->messageSend($body,$deviceId,$from,$type,true);

        $apiKey = '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';
        $apiUrl = 'https://api.openai.com/v1/chat/completions'; // Replace with the correct API endpoint
        $imagesApiUrl = 'https://api.openai.com/v1/images/generations'; // Replace with the correct API endpoint

        $client = new Client();

        try {
if(false){
            $imageResponse = $client->post($imagesApiUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $apiKey,
                ],
                'json' =>
                [
                    "prompt"=> $user_message,
                    "n"=>1
            ]


            ]);

            $imageResult = json_decode($imageResponse->getBody()->getContents(), true);


            $body['attachment'] = $imageResult['data'][0]['url'];
            $body['message'] = "";
            $type='text-with-image-media';

             $this->messageSend($body,$deviceId,$from,$type);
            }

            $response = $client->post($apiUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $apiKey,
                ],
                'json' =>
                    [
                        "model"=> "gpt-3.5-turbo",
                        "messages"=> [
                            ["role"=> "user", "content"=> "replay to ".$user_message]
                        ],
                        "temperature"=> 0.5,
                        "max_tokens"=>1500
                    ]


            ]);


            $result = json_decode($response->getBody()->getContents(), true);
            return response()->json([
                'message'  => array('text' => $result['choices'][0]['message']['content']),
                'receiver' => $from,
                'session_id' => $session
            ],200);

        } catch (\Exception $e) {
            // Handle exceptions (e.g., connection error, invalid API response)
            $body['text'] = "فشل الرد علي رسالتك حاول مره اخري !".$e->getMessage();
            $type='plain-text';

            $response= $this->messageSend($body,$deviceId,$from,$type,true);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
