<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
class ApiAuthController extends Controller {

    // {
    //     "event_type": "message_received",
    //     "instanceId": "43010551-ac66-444c-9109-a6c51654fe69",
    //     "data": {
    //       "id": "249998887970@s.whatsapp.net_26498C0B44C2AB7EC05B7A92F0881AB7",
    //       "from": "249998887970",
    //       "to": "971561500873",
    //       "ack": "pending",
    //       "type": "chat",
    //       "body": "Hello",
    //       "imageMessage": null,
    //       "fromMe": false,
    //       "isForwarded": false,
    //       "time": 1696014111
    //     }
    //   }
    public function send( Request $request){


        try {

            $webPush = new WebPush([
                "VAPID" => [
                    "publicKey" => "BC5zel9JoqeOY2yVTJjDhiE1IisJTVHq-_p4rxC3zd60gQSqXzra_7_m7B12axwI42tZIUXYGXhIJ-t5MolKjNY",
                    "privateKey" => "YpOYF6OwLXH8PDW24E4Eu_kk7uOuSyApvC0NJhYNwa4",
                    "subject" => url(""),

                ]
            ]);

            $event = [
                "title"=>"new message come" , "body"=>$request->data['body']??'' , "url"=>"/user/dashboard"
            ];

            $result = $webPush->sendOneNotification(
                Subscription::create([
                    "endpoint"=> "https://fcm.googleapis.com/fcm/send/e-W38P5or3M:APA91bHcK42thFnqbMHJ8NQAX1c9Je0YYqRv8M0RB9Ur8yaK9luMu1NrYdgh2NtNz-2U0a3tfsJi1n70wSJ52RrcElAaiDyN-cDTiLBfjgXlNKo-p3pYvfwVEzOQSXdXsw56NvmrmxjJ",
                    "expirationTime"=>  null,
                    "keys"=>  [
                        "p256dh"=>  "BMa_vnJwUh9AcKu0cRrObKa-syBo2i7RtT8B5qJUcgAQJ_Zwg1hRvo5gcduJnfPcDW-nF6nbjHkuTN3KP2i4-8A",
                        "auth"=>  "FnKChhO1KdLBSxgrCWhSjw"
                    ]
                ]),
                json_encode($event )
            );
            dd( $result );
            response()->json($result, 200);
        }catch (Exception $e) {
            // Code to handle the exception


            $data ="Caught exception: " . $e->getMessage()."\n";

        }
    }
    public function subscribe(Request $request){
return  response()->json($request->getContent(), 200);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            // $token = $user->createToken('auth-token')->plainTextToken;
            if ($user->apiToken) {
                $token = $user->apiToken;

            }else{

                $token = $user->createToken('auth-token')->plainTextToken;

                $user->apiToken=$token;

                $user->save();
            }


            return response()->json(['token' => $token], 200);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
