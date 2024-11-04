@extends('frontend.main_master')
@section('title')
    OTP Verification | India's No 1 Online Saree Shop - Nithitex
@endsection
@section('content')
    <div class="breadcrumb-area bg-gray">
        <div class="container">
            <div class="breadcrumb-content text-center">
                <ul>
                    <li>
                        <a href="/">Home</a>
                    </li>
                    <li class="active">OTP Verification</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container mt-50 mb-50">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <strong class="card-header">{{ __('OTP Verification') }}</strong>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert"> {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger" role="alert"> {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('otp.getlogin') }}">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user_id }}" />
                            <input type="hidden" name="device_id" class="device_id" value="">
                            <input type="hidden" name="fcm_token" class="fcm_token" value="{{ $fcm_token }}">
                            <div class="row mb-3">
                                <strong for="mobile_no"
                                    class="col-md-4 col-form-label text-md-end">{{ __('OTP') }}</strong>

                                <div class="col-md-6">
                                    <input id="otp" type="text"
                                        class="form-control @error('otp') is-invalid @enderror" name="otp"
                                        value="{{ old('otp') }}" required autocomplete="otp" autofocus
                                        placeholder="Enter OTP">
                                    <div class="mt-15">OTP Will Expire In = <span id="timer"
                                            class="text-danger"></span></div>

                                    @error('otp')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>



                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-6">
                                    <button type="submit" class="btn btn-success">
                                        {{ __('Verify') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script type="text/javascript">
    window.onload = function() {
        document.getElementById("timer").innerHTML = 04 + ":" + 59;
        startTimer();

        function startTimer() {
            var presentTime = document.getElementById("timer").innerHTML;
            var timeArray = presentTime.split(/[:]+/);
            var m = timeArray[0];
            var s = checkSecond(timeArray[1] - 1);
            if (s == 59) {
                m = m - 1;
            }
            if (m < 0) {
                return;
            }

            document.getElementById("timer").innerHTML = m + ":" + s;
            console.log(m);
            setTimeout(startTimer, 1000);
        }

        function checkSecond(sec) {
            if (sec < 10 && sec >= 0) {
                sec = "0" + sec;
            } // add zero in front of numbers < 10
            if (sec < 0) {
                sec = "59";
            }
            return sec;
        }
    }

    $(document).ready(function() {
        FingerprintJS.load().then(fp => {
            fp.get().then(result => {
                var device_id = result.visitorId;
                $('.device_id').val(device_id);
            });

        });
    });
</script>
