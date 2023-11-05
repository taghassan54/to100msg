@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection')
@endsection
@section('content')

<div class="row justify-content-center">
   <div class="col-12">

      @if(count($devices ?? []) > 0)
      <div class="row">
      @foreach($devices ?? [] as $device)
      <div class="col-xl-4 col-md-6">
         <div class="card  border-0">
            <!-- Card body -->
            <div class="card-body">
               <div class="row">
                  <div class="col">
                     <h5 class="card-title text-uppercase text-muted mb-0 text-dark">{{ $device->name }}</h5>
                     <div class="mt-3 mb-0">
                        <span class="pt-2 text-dark">{{__('Phone :')}}

                        @if(!empty($device->phone))
                        <a href="{{ __('/user/chatbot?deviceId='.$device->uuid) }}" target="_blank">
                        {{ $device->phone  }}
                        </a>
                        @endif
                        </span>
                        <br>
                        <br>
                        <span class="pt-2 text-dark"><i class="fas fa-copy" id="copyButton"></i> {{__('uuid :')}}

                     @if(!empty($device->uuid))
                     <a href="{{ __('/user/chatbot?deviceId='.$device->uuid) }}" target="_blank">
                     <span id="textToCopy">{{ $device->uuid  }}</span>
                     </a>

                     @endif
                     </span>
                     <br>
                        <span class="pt-2 text-dark">{{__('Total Messages:')}} {{ number_format($device->smstransaction_count) }}
                     </div>
                  </div>

               </div>
               <p class="mt-3 mb-0 text-sm">
                  <a class="text-nowrap  font-weight-600" href="{{ route('user.device.scan',$device->uuid) }}">
                  <span class="text-dark">{{ __('Status :') }}</span>
                  <span class="badge badge-sm {{ badge($device->status)['class'] }}">
                  {{ $device->status == 1 ? __('Active') : __('Inactive')  }}
                  </span>
                  </a>
               </p>
            </div>
         </div>
      </div>
      @endforeach
      </div>
      @else
      <div class="alert  bg-gradient-primary text-white"><span class="text-left">{{ __('Opps There Is No Device Found....') }}</span></div>
      @endif
   </div>
</div>

@endsection
@push('js')
<script src="{{ asset('assets/js/pages/user/chatbot.js') }}"></script>
@endpush
