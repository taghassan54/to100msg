<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['throttle:api']], function (){
//$logs['message_content'] = json_encode($body);
    Route::post('create-message','App\Http\Controllers\Api\BulkController@submitRequest')->name('api.create.message');
    Route::post('/set-device-status/{device_id}/{status}','App\Http\Controllers\Api\BulkController@setStatus');
    Route::post('/send-webhook/{device_id}','App\Http\Controllers\Api\BulkController@webHook');
    Route::get('/phoneCodes','App\Http\Controllers\Api\BulkController@phoneCodes');


    Route::post('login',           [\App\Http\Controllers\Api\ApiAuthController::class,'login']);
    Route::post('push-subscribe',           [\App\Http\Controllers\Api\ApiAuthController::class,'subscribe']);
    Route::post('push-send',           [\App\Http\Controllers\Api\ApiAuthController::class,'send']);

    Route::middleware(['auth:sanctum'])->group(function () {

    Route::resource('device',                    \App\Http\Controllers\Api\DeviceController::class);
    Route::get('device/{id}/info',                 [\App\Http\Controllers\Api\DeviceController::class,'getInfo'])->name('device.getInfo');
    Route::post('create-session/{id}',           [\App\Http\Controllers\Api\DeviceController::class,'getQr']);
    Route::post('check-session/{id}',            [\App\Http\Controllers\Api\DeviceController::class,'checkSession']);
    Route::post('getGroupList/{id}',            [\App\Http\Controllers\Api\DeviceController::class,'groupHistory']);
    Route::post('getChats/{id}',            [\App\Http\Controllers\Api\DeviceController::class,'chatHistory']);
    Route::post('/send-group-message/{uuid}',            [\App\Http\Controllers\Api\BulkController::class,'sendGroupMessage']);
    Route::get('/smsTransactions',            [\App\Http\Controllers\Api\BulkController::class,'smsTransactions']);
    Route::get('/dashboardData',            [\App\Http\Controllers\Api\DeviceController::class,'dashboardData']);
    Route::post('/logout-session/{id}',          [\App\Http\Controllers\Api\DeviceController::class,'logoutSession']);
    Route::post('/device-statics',               [\App\Http\Controllers\Api\DeviceController::class,'deviceStatics']);
    Route::post('device/{id}/updateForChatBot',                 [\App\Http\Controllers\Api\DeviceController::class,'updateForChatBot']);
       //chatbot route
   Route::resource('chatbot',                            \App\Http\Controllers\Api\ChatbotController::class);
   Route::post('chatbot/{id}/update',                            [\App\Http\Controllers\Api\ChatbotController::class,'update']);
});


});
