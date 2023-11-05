@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['title'=> 'Automatic Replies','buttons'=>[
[
'name'=>'<i class="fas fa-plus"></i> &nbspCreate Reply',
'url'=>'#',
'components'=>'data-toggle="modal" data-target="#send-template-bulk" id="send-template-bulks"',
'is_button'=>true
]
]])
@endsection
@section('content')
<div class="row justify-content-center">
<!--  -->

<!--  -->
	<div class="col-12">
		<div class="row d-flex justify-content-between flex-wrap">
			<div class="col">
				<div class="card card-stats">
					<div class="card-body">
						<div class="row">
							<div class="col">
								<span class="h2 font-weight-bold mb-0 total-transfers" >
									{{ number_format($total_replies) }}
								</span>
							</div>
							<div class="col-auto">
								<div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
									<i class="fas  fa-robot"></i>
								</div>
							</div>
						</div>
						<p class="mt-3 mb-0 text-sm">
						</p><h5 class="card-title  text-muted mb-0">{{ __('Total Replies') }}</h5>
						<p></p>
					</div>
				</div>
			</div>
			<div class="col">
				<div class="card card-stats">
					<div class="card-body">
						<div class="row">
							<div class="col">
								<span class="h2 font-weight-bold mb-0 total-transfers" >
									{{ number_format($template_replies) }}
								</span>
							</div>
							<div class="col-auto">
								<div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
									<i class="fi fi-rs-test mt-2"></i>
								</div>
							</div>
						</div>
						<p class="mt-3 mb-0 text-sm">
						</p><h5 class="card-title  text-muted mb-0">{{ __('Template Replies') }}</h5>
						<p></p>
					</div>
				</div>
			</div>
			<div class="col">
				<div class="card card-stats">
					<div class="card-body">
						<div class="row">
							<div class="col">
								<span class="h2 font-weight-bold mb-0 completed-transfers" >
									{{ number_format($text_replies) }}
								</span>
							</div>
							<div class="col-auto">
								<div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
									<i class=" fi-rs-text-check mt-2"></i>
								</div>
							</div>
						</div>
						<p class="mt-3 mb-0 text-sm">
						</p><h5 class="card-title  text-muted mb-0">{{ __('Text Replies') }}</h5>
						<p></p>
					</div>
				</div>
			</div>
		</div>
		@if(getUserPlanData('chatbot') == false)
		<div class="row">
			<div class="col-sm-12">
				<div class="alert bg-gradient-primary text-white alert-dismissible fade show" role="alert">
					<span class="alert-icon"><i class="fi  fi-rs-info text-white"></i></span>
					<span class="alert-text">
						<strong>{{ __('!Opps ') }}</strong>

						{{ __('Chatbot features is not available in your subscription plan') }}

					</span>
				</div>
			</div>
		</div>
		@endif
		@if(count($replies ?? []) == 0)
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<center>
							<img src="{{ asset('assets/img/404.jpg') }}" height="500">
							<h3 class="text-center">{{ __('!Opps You Have Not Created Automatic Reply') }}</h3>
							<a href="#" data-toggle="modal" data-target="#send-template-bulk" id="send-template-bulks" class="btn btn-neutral"><i class="fas fa-plus"></i> {{ __('Create a reply') }}</a>
						</center>
					</div>
				</div>
			</div>
		</div>
		@else
		<div class="card">

			<div class="card-body">
				<div class="row">
					<div class="col-lg-3 col-md-3 col-12 table-responsive">

<form method="POST" action="{{ url('/user/device/'.$devices->uuid.'/updateForChatBot') }}" id="botDevice">
    @csrf
    @method('POST')

    <div class="col-sm-12 d-flex mb-3">
                                                <label class="custom-toggle custom-toggle-primary">
                                                    <input type="checkbox" name="bot_status" id="bot_status" data-target=".bot_status" class="save-template" {{ $devices->bot_status ? 'checked' : '' }}>
                                                    <span class="custom-toggle-slider rounded-circle" data-label-off="Yes" data-label-on="Yes"></span>
                                                </label>
                                                <label class="mt-1 ml-1" for="plain-text-with-button"><h4>Bot Status?</h4></label>
                                            </div>



    <div class="mb-3">
        <label for="device">Device Name:</label>
        <input name="name" class="form-control" id="name" value={{ $devices->name }} readonly>
    </div>



                                            <div class="col-sm-12 d-flex mb-3">
                                                <label class="custom-toggle custom-toggle-primary">
                                                    <input type="checkbox" name="welcomeMessageEnable" id="welcomeMessageEnable" data-target=".welcomeMessageEnable" class="save-template" {{ $devices->welcomeMessageEnable ? 'checked' : '' }}>
                                                    <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                </label>
                                                <label class="mt-1 ml-1" for="welcomeMessageEnable"><h4>Welcome Message Enable?</h4></label>
                                            </div>

    <div class="mb-3">
        <label for="welcomeMessage">Welcome Message:</label>
        <textarea name="welcomeMessage" class="form-control" id="welcomeMessage">{{ $devices->welcomeMessage }}</textarea>
    </div>


    <div class="col-sm-12 d-flex mb-3">
                                                <label class="custom-toggle custom-toggle-primary">
                                                    <input type="checkbox" name="errorMessageEnable" id="plain-text-with-button" data-target=".plain-title-with-button" class="save-template" {{ $devices->errorMessageEnable ? 'checked' : '' }}>
                                                    <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                </label>
                                                <label class="mt-1 ml-1" for="plain-text-with-button"><h4>Error Message Enable?</h4></label>
                                            </div>


    <div class="mb-3">
        <label for="errorMessage">Error Message:</label>
        <textarea name="errorMessage" class="form-control" id="errorMessage">{{ $devices->errorMessage }}</textarea>
    </div>






                                            <div class="mb-3">
        <label for="resendToMain">Resend To Main:</label>
        <input type="number" name="resendToMain" class="form-control" id="resendToMain" value="{{ $devices->resendToMain }}">
    </div>

    <div class="mb-3">
        <label for="webhook_url">Webhook URL:</label>
        <input type="url" name="webhook_url" class="form-control" id="webhook_url" value="{{ $devices->webhook_url }}">
    </div>

    <button type="submit" class="btn btn-info">Update Settings</button>
</form>

                    </div>
					<div class="col-lg-9 col-12 table-responsive">
						<table class="table col-12">
							<thead>
								<tr>
									<th>{{ __('Keyword') }}</th>
									<th>{{ __('Device') }}</th>
									<th>{{ __('Reply Type') }}</th>
									<th>{{ __('Keyword Match Type') }}</th>
									<th class="text-right">{{ __('Action') }}</th>
								</tr>
							</thead>
							<tbody class="tbody">
								@foreach($replies ?? [] as $reply)
								<tr>
									<td>{{ $reply->keyword }}</td>
									<td>{{ $reply->device->phone ?? '' }}</td>
									<td>{{ $reply->reply_type }}</td>
									<td>{{ $reply->match_type == 'equal' ? 'Whole Word' : 'Similar Word' }}</td>
									<td>
										<div class="btn-group mb-2 float-right">
											<button class="btn btn-neutral btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												{{ __('Action') }}
											</button>
											<div class="dropdown-menu">
												<a class="dropdown-item has-icon edit-reply" href="#"
												data-action="{{ route('user.chatbot.update',$reply->id) }}"
												data-templateid="{{ $reply->template_id }}"
												data-keyword="{{ $reply->keyword }}"
												data-reply="{{ $reply->reply }}"
												data-matchtype="{{ $reply->match_type }}"
												data-replytype="{{ $reply->reply_type }}"
												data-device="{{ $reply->device_id }}"
												data-toggle="modal"
												data-target="#editModal"
												>
												<i class="ni ni-align-left-2"></i>{{ __('Edit') }}</a>
												<a class="dropdown-item has-icon delete-confirm" href="javascript:void(0)" data-action="{{ route('user.chatbot.destroy',$reply->id) }}"><i class="fas fa-trash"></i>{{ __('Remove Reply') }}</a>
											</div>
										</div>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						<div class="d-flex justify-content-center">{{ $replies->links('vendor.pagination.bootstrap-4') }}</div>
					</div>
				</div>
			</div>
		</div>
		@endif
	</div>
</div>

<div class="modal fade" id="send-template-bulk" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form type="POST" action="{{ route('user.chatbot.store') }}" class="ajaxform_instant_reload">
				@csrf
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">{{ __('Create Reply') }}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label>{{ __('Keyword') }}</label>
						<input type="text" name="keyword" class="form-control" name="keyword" maxlength="50">
					</div>

                    <div class="form-group file none">
						<label>{{ __('file') }}</label>
						<input type="text" name="file" class="form-control" name="file" maxlength="50">
					</div>

					<div class="form-group">
						<label>{{ __('Select Device') }} : {{  $devices->name }}</label>
						{{-- <select  class="form-control" name="device">
							@foreach($devices as $device)
							<option value="{{  $device->id }}">{{ $device->name . ' - '. $device->phone }}</option>
							@endforeach
						</select> --}}
                        <input type="text" class="form-control" name="device" value="{{  $devices->id }}" id="" readonly>
					</div>
					<div class="form-group">
						<label>{{ __('Reply Type') }}</label>
						<select  class="form-control reply_type" name="reply_type">
							<option value="text">{{ __('Plain Text') }}</option>
							<option value="file">{{ __('file') }}</option>
							<option value="contact">{{ __('contact') }}</option>
							<option value="location">{{ __('location') }}</option>
							<option value="vcard">{{ __('vcard') }}</option>
							<option value="template">{{ __('Template') }}</option>

						</select>
					</div>
					<div class="form-group">
						<label>{{ __('Keyword Match Type') }}</label>
						<select  class="form-control" name="match_type">
							<option value="equal">{{ __('Whole Words') }}</option>

						</select>
					</div>
					<div class="form-group text-area">
						<label>{{ __('Reply') }}</label>
						<textarea class="form-control" name="reply" maxlength="1000"></textarea>
					</div>
					<div class="form-group templates none">
						<label>{{ __('Select Template') }}</label>
						<select  class="form-control" name="template">
							@foreach($templates ?? [] as $template)
							<option value="{{ $template->id }}">{{ $template->title }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-neutral submit-btn float-right">{{ __('Create Now') }}</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form type="POST" action="" class="ajaxform_instant_reload edit-reply-form">
				@csrf
				@method('PUT')
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">{{ __('Edit Reply') }}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
					<div class="form-group">
						<label>{{ __('Keyword') }}</label>
						<input type="text" name="keyword" class="form-control" id="keyword" required="" maxlength="50" name="keyword">
					</div>
					<div class="form-group">
						<label>{{ __('Select Device') }} : {{  $devices->name }}</label>
						{{-- <select  class="form-control" name="device" id="device">
							@foreach($devices as $device)
							<option value="{{  $device->id }}">{{ $device->name . ' - '. $device->phone }}</option>
							@endforeach
						</select> --}}
                        <input type="text" class="form-control" name="device" value="{{  $devices->id }}" id="" readonly>
					</div>

					<div class="form-group">
						<label>{{ __('Reply Type') }}</label>
						<select  class="form-control reply_type" name="reply_type" id="replytype">
							<option value="text">{{ __('Plain Text') }}</option>
							<option value="template">{{ __('Template') }}</option>
						</select>
					</div>
					<div class="form-group">
						<label>{{ __('Keyword Match Type') }}</label>
						<select  class="form-control" name="match_type" id="matchtype">
							<option value="equal">{{ __('Whole Words') }}</option>

						</select>
					</div>
					<div class="form-group text-area" id="reply-area">
						<label>{{ __('Reply') }}</label>
						<textarea class="form-control" name="reply" maxlength="1000" id="reply"></textarea>
					</div>
					<div class="form-group templates none" id="templates-area">
						<label>{{ __('Select Template') }}</label>
						<select  class="form-control" name="template" id="templateid">
							@foreach($templates ?? [] as $template)
							<option value="{{ $template->id }}">{{ $template->title }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-neutral submit-btn float-right">{{ __('Update Reply') }}</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection
@push('js')
<script src="{{ asset('assets/js/pages/user/chatbot.js') }}"></script>

<script>
        $(document).ready(function () {
            // Use the form's ID to target it
            $('#botDevice').submit(function (e) {
                e.preventDefault(); // Prevent the form from submitting traditionally

                // Use AJAX to submit the form data
                $.ajax({
                    type: 'POST', // Set the method to POST
                    url: $(this).attr('action'), // Get the form action URL
                    data: $(this).serialize(), // Serialize the form data
                    success: function (response) {
                        // Handle the success response here
                        console.log(response);
                        NotifyAlert('success', response);
                    },
                    error: function (error) {
                        // Handle the error response here
                        console.log(error);
                    }
                });
            });
        });
    </script>
@endpush
