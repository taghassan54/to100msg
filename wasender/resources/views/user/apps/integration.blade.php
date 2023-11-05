@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['buttons'=>[
	[
		'name'=>'Back',
		'url'=>route('user.apps.index'),
	]
]])
@endsection
@section('content')

@php
                            $url=route('api.create.message');
                            @endphp
<div>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0 font-weight-bolder">{{__('Create New Message')}}</h3>
                    </div>


                    <div class="card card alpha-success border-success" style="">
<div class="card-body p-0">
<table class="table">
<thead>
<tr>
<th class="p-2 text-center">Status</th>
<th class="p-2 text-center battery" style="display:none"></th>
<th class="p-2 text-center">API URL</th>
<th class="p-2 text-center">Device Id</th>
<th class="p-2 text-center">App Key</th>
<th class="p-2 text-center">Auth Key</th>
</tr>
</thead>
<tbody>
<tr>
<td class="p-2 text-center"><span class="h3"> <span class="badge badge-sm {{ badge($device->status)['class'] }}">
                  {{ $device->status == 1 ? __('Active') : __('Inactive')  }}
                  </span> </span></td>
<td class="p-2 text-center battery" style="display:none"><span class="h3"><span class="badge badge-warning battery-percent"></span></span></td>
<td class="p-2 text-center">
<div class="input-group dltr">
<input type="text" class="form-control" readonly="" value="{{ $url }}">
<span class="input-group-append">
<button class="btn btn-light  js-copy copy-button" type="button" data-copy="{{ $url }}"><i class="far fa-copy"></i></button>
</span>
</div>
</td>
<td class="p-2 text-center">
<div class="input-group dltr">
<input type="text" class="form-control" readonly="" value="{{$deviceId}}">
<span class="input-group-append">
<button class="btn btn-light  js-copy copy-button" type="button" data-copy="{{$deviceId}}"><i class="far fa-copy"></i></button>
</span>
</div>
</td>
<td class="p-2 text-center">
<div class="input-group dltr">
<input type="text" class="form-control" readonly="" value="{{$key}}">
<span class="input-group-append">
<button class="btn btn-light  js-copy copy-button" type="button" data-copy="{{$key}}"><i class="far fa-copy"></i></button>
<button class="btn btn-light" type="button" onclick="change_instance_token()"><i class="fas fa-sync-alt"></i></button>
</span>
</div>
</td>
<td class="p-2 text-center">
<div class="input-group dltr">
<input type="text" class="form-control" readonly="" value="Bearer {{\Auth::user()->apiToken}}">
<span class="input-group-append">
<button class="btn btn-light  js-copy copy-button"  type="button" data-copy="Bearer {{\Auth::user()->apiToken}}"><i class="far fa-copy"></i></button>
<button class="btn btn-light" type="button" onclick="change_instance_token()"><i class="fas fa-sync-alt"></i></button>
</span>
</div>
</td>
</tr>
</tbody>
</table>
</div>
</div>


                    <div class="card-body">
                        <div class="">


                            <ul class="nav nav-pills nav-fill" id="myTab" role="tablist">
                              <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" data-target="#curl" type="button" role="tab" aria-controls="home" aria-selected="true">cUrl</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" data-target="#php" type="button" role="tab" aria-controls="profile" aria-selected="false">Php</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" data-target="#nodejs" type="button" role="tab" aria-controls="profile" aria-selected="false">NodeJs - Request</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="contact-tab" data-toggle="tab" data-target="#python" type="button" role="tab" aria-controls="contact" aria-selected="false">Python</a>
                            </li>
                        </ul>

<div class="tab-content mt-4 mb-4" id="myTabContent">
<div class="tab-pane fade show active" id="curl" role="tabpanel" aria-labelledby="home-tab">
    <div class="language-markup">

<pre class="language-markup" tabindex="0">
<h3>{{ __('Text Message Only') }}</h3>
curl --location --request POST '{{ $url }}' \
--form 'deviceId="{{ $deviceId }}"' \
--form 'appkey="{{ $key }}"' \
--form 'authkey="{{ Auth::user()->authkey }}"' \
--form 'to="RECEIVER_NUMBER"' \
--form 'message="Example message"' \
</pre>
</div>
<hr>
<div class="language-markup">

<pre class="language-markup" tabindex="0">
<h3>{{ __('Text Message with file') }}</h3>
curl --location --request POST '{{ $url }}' \
--form 'deviceId="{{ $deviceId }}"' \
--form 'appkey="{{ $key }}"' \
--form 'authkey="{{ Auth::user()->authkey }}"' \
--form 'to="RECEIVER_NUMBER"' \
--form 'message="Example message"' \
--form 'file="https://www.africau.edu/images/default/sample.pdf"'
</pre>
<div class="alert alert-success">

[
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
]

</div>
</div>
<hr>
<div class="language-markup">
    <pre class="language-markup" tabindex="2">
<code class="language-markup">
<h3>{{ __('Template Only') }}</h3>
curl --location --request POST '{{ $url }}' \
--form 'deviceId="{{ $deviceId }}"' \
--form 'appkey="{{ $key }}"' \
--form 'authkey="{{ Auth::user()->authkey }}"' \
--form 'to="RECEIVER_NUMBER"' \
--form 'template_id="TEMPLATE_ID"' \
--form 'variables[{variableKey1}]="jhone"' \
--form 'variables[{variableKey2}]="replaceable value"'

</code>

</pre>
</div>
</div>

  <div class="tab-pane fade" id="php" role="tabpanel" aria-labelledby="profile-tab">
      <pre class="language-markup" tabindex="1">
<h3>{{ __('Text Message Only') }}</h3>
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => '{{ $url }}',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array(
  'deviceId' => '{{ $deviceId }}',
  'appkey' => '{{ $key }}',
  'authkey' => '{{ Auth::user()->authkey }}',
  'to' => 'RECEIVER_NUMBER',
  'message' => 'Example message',
  'sandbox' => 'false'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
</pre>
<hr>
<pre class="language-markup" tabindex="1">
<h3>{{ __('Text Message with file') }}</h3>
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => '{{ $url }}',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array(
  'appkey' => '{{ $key }}',
  'deviceId' => '{{ $deviceId }}',
  'authkey' => '{{ Auth::user()->authkey }}',
  'to' => 'RECEIVER_NUMBER',
  'message' => 'Example message',
  'file' => 'https://www.africau.edu/images/default/sample.pdf',
  'sandbox' => 'false'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
</pre>
<div class="alert alert-success">

[
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
]

</div>
<hr>
 <pre class="language-markup" tabindex="1">
<h3>{{ __('Template Message Only') }}</h3>
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => '{{ $url }}',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array(
  'deviceId' => '{{ $deviceId }}',
  'appkey' => '{{ $key }}',
  'authkey' => '{{ Auth::user()->authkey }}',
  'to' => 'RECEIVER_NUMBER',
  'template_id' => 'TEMPLATE_ID',
  'variables' => array(
    '{variableKey1}' => 'Jhone',
    '{variableKey2}' => 'replaceable value'
   )
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
</pre>



  </div>
  <div class="tab-pane fade" id="nodejs" role="tabpanel" aria-labelledby="contact-tab">
<pre class="language-markup" tabindex="2">
<code class="language-markup">
<h3>{{ __('Text Message Only') }}</h3>
var request = require('request');
var options = {
  'method': 'POST',
  'url': '{{ $url }}',
  'headers': {
  },
  formData: {
    'deviceId' : '{{ $deviceId }}',
    'appkey': '{{ $key }}',
    'authkey': '{{ Auth::user()->authkey }}',
    'to': 'RECEIVER_NUMBER',
    'message': 'Example message'
  }
};
request(options, function (error, response) {
  if (error) throw new Error(error);
  console.log(response.body);
});

</code>
</pre>
<hr>
<pre class="language-markup" tabindex="2">
<code class="language-markup">
<h3>{{ __('Text Message With File') }}</h3>
var request = require('request');
var options = {
  'method': 'POST',
  'url': '{{ $url }}',
  'headers': {
  },
  formData: {
    'deviceId' : '{{ $deviceId }}',
    'appkey': '{{ $key }}',
    'authkey': '{{ Auth::user()->authkey }}',
    'to': 'RECEIVER_NUMBER',
    'message': 'Example message',
    'file': 'https://www.africau.edu/images/default/sample.pdf'
  }
};
request(options, function (error, response) {
  if (error) throw new Error(error);
  console.log(response.body);
});

</code>

</pre>
<div class="alert alert-success">

[
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
]

</div>
<hr>
<pre class="language-markup" tabindex="2">
<code class="language-markup">
<h3>{{ __('Template Only') }}</h3>
var request = require('request');
var options = {
  'method': 'POST',
  'url': '{{ $url }}',
  'headers': {
  },
  formData: {
    'deviceId' : '{{ $deviceId }}',
    'appkey': '{{ $key }}',
    'authkey': '{{ Auth::user()->authkey }}',
    'to': 'RECEIVER_NUMBER',
    'template_id': 'SELECTED_TEMPLATE_ID',
    'variables': {
        '{variableKey1}' : 'jhone',
        '{variableKey2}' : 'replaceable value'
    }
  }
};
request(options, function (error, response) {
  if (error) throw new Error(error);
  console.log(response.body);
});

</code>
</pre>

  </div>
  <div class="tab-pane fade" id="python" role="tabpanel" aria-labelledby="contact-tab">
       <pre class="language-markup" tabindex="3">
<code class="language-markup">
import requests

url = "{{ $url }}"

payload={
'deviceId' : '{{ $deviceId }}',
'appkey': '{{ $key }}',
'authkey': '{{ Auth::user()->authkey }}',
'to': 'RECEIVER_NUMBER',
'message': 'Example message',

}
files=[

]
headers = {}

response = requests.request("POST", url, headers=headers, data=payload, files=files)

print(response.text)



</code></pre>
<div class="alert alert-success">

[
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
]

</div>
  </div>


</div>
                        </div>

                        <h3 class="font-weight-bolder">{{__('Successful Json Callback')}}</h3>
                        <pre>
<code>
 {
    "message_status": "Success",
    "data": {
        "from": "SENDER_NUMBER",
        "to": "RECEIVER_NUMBER",
        "status_code": 200
    }
}
</code>
                    </pre>
                    </div>
                </div>



                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0 font-weight-bolder">{{__('Parameters')}}</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-flush">
                            <thead class="">
                            <tr>
                                <th>{{__('S/N')}}</th>
                                <th>{{__('Value')}}</th>
                                <th>{{__('Type')}}</th>
                                <th>{{__('Required')}}</th>
                                <th>{{__('Description')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{__('1.')}}</td>
                                <td>{{__('appkey')}}</td>
                                <td>{{__('string')}}</td>
                                <td>{{__('Yes')}}</td>
                                <td>{{ __("Used to authorize a transaction for the app") }}</td>
                            </tr>
                            <tr>
                                <td>{{__('2.')}}</td>
                                <td>{{__('authkey')}}</td>
                                <td>{{__('string')}}</td>
                                <td>{{__('Yes')}}</td>
                                <td>{{ __("Used to authorize a transaction for the is valid user") }}</td>
                            </tr>
                            <tr>
                                <td>{{__('3.')}}</td>
                                <td>{{__('to')}}</td>
                                <td>{{__('number')}}</td>
                                <td>{{__('Yes')}}</td>
                                <td>{{ __("Who will receive the message the Whatsapp number should be full number with country code") }}</td>
                            </tr>
                            <tr>
                                <td>{{__('4.')}}</td>
                                <td>{{__('template_id')}}</td>
                                <td>{{__('string')}}</td>
                                <td>{{__('No')}}</td>
                                <td>{{ __("Used to authorize a transaction for the template") }}</td>
                            </tr>
                           	<tr>
                                <td>{{__('5.')}}</td>
                                <td>{{__('message')}}</td>
                                <td>{{__('string')}}</td>
                                <td>{{__('No')}}</td>
                                <td>{{ __("The transactional message max:1000 words") }}</td>
                            </tr>
                            <tr>
                                <td>{{__('6.')}}</td>
                                <td>{{__('file')}}</td>
                                <td>{{__('string')}}</td>
                                <td>{{__('No')}}</td>
                                <td>{{ __("file extension type should be in jpg,jpeg,png,webp,pdf,docx,xlsx,csv,txt") }}</td>
                            </tr>
                            <tr>
                                <td>{{__('7.')}}</td>
                                <td>{{__('variables')}}</td>
                                <td>{{__('Array')}}</td>
                                <td>{{__('No')}}</td>
                                <td>{{ __("the first value you list replaces the {1} variable in the template message and the second value you list replaces the {2} variable") }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const copyButtons = document.querySelectorAll('.copy-button');

            copyButtons.forEach(button => {
                button.addEventListener("click", function () {
                  const dataValue = this.getAttribute("data-copy");
                  if (dataValue) {
                        // const textToCopy = targetElement.getAttribute("data-copy");
                        const tempInput = document.createElement("input");
                        tempInput.value = dataValue;
                        document.body.appendChild(tempInput);
                        tempInput.select();
                        document.execCommand("copy");
                        document.body.removeChild(tempInput);
                        alert("Copied to clipboard: " + dataValue);
                    }
                });
            });
        });
    </script>

@endsection
