@extends('frontend.main_master')
@section('content')
@section('title')
    India's No 1 Online Saree Shop - Nithitex
@endsection
<div class="login-register-area pt-80 pb-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-12 ms-auto me-auto">
                <div class="login-register-wrapper">
                    <div class="login-register-tab-list nav">
                        <a class="active" data-bs-toggle="tab" href="#lg1">
                            <h4> login </h4>
                        </a>
                        <a data-bs-toggle="tab" href="#lg2">
                            <h4> register </h4>
                        </a>
                    </div>
                    <div class="tab-content">
                        <div id="lg1" class="tab-pane active">
                            <div class="login-form-container">
                                <div class="login-register-form">
                                    <form method="POST" action="{{ route('user.storing') }}">
                                        @csrf
                                        <input type="hidden" name="device_id" class="device_id" value="">
                                        <input type="hidden" name="fcm_token" class="fcm_token" value="">
                                        <input type="text" name="username" id="username" class="form-control"
                                            placeholder="Enter your email/mobile" required autocomplete="off" />
                                        @error('username')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <input id="password-field" type="password" class="form-control"
                                            placeholder="Enter your password" name="password" autocomplete="off">
                                        <span toggle="#password-field"
                                            class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <div class="button-box">
                                            <div class="login-toggle-btn">
                                                <a href="{{ route('user.forget.password.get') }}">Forgot Password?</a>
                                            </div>
                                            <button type="submit" name="" id="">Login</button>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <ul class="p-0 m-0" style="list-style: none;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div id="lg2" class="tab-pane">
                            <div class="login-form-container">
                                <div class="login-register-form">
                                    <form method="POST" action="{{ route('user.register.store') }}" id="upload_form">
                                        @csrf
                                        <input type="hidden" name="device_id" class="device_id" value="">
                                        <input type="hidden" name="fcm_token" class="fcm_token" value="">

                                        <input type="text" name="name" id="name" placeholder="Name" required
                                            autocomplete="off">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <input type="text" name="phone" id="phone" placeholder="Mobile number"
                                            required autocomplete="off" />
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <input type="email" name="email" id="email" placeholder="E-mail"
                                            required autocomplete="off">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <input type="password" name="password" id="password" placeholder="Password"
                                            required autocomplete="off">
                                        <span toggle="#password"
                                            class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <input type="password" name="password_confirmation"
                                            id="password_confirmation" placeholder="Confirm Password" required
                                            autocomplete="off">
                                        <span toggle="#password_confirmation"
                                            class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        @error('password_confirmation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <div class="button-box">
                                            <button type="submit">Register</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js"></script>
{{-- Get FCM token --}}
<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/firebase-messaging-sw.js')
            .then((registration) => {
                const firebaseConfig = {
                    apiKey: "AIzaSyBsv1XYf2bVeZE-wMTk2OVxZlp9ayTLwDg",
                    authDomain: "nithitex-8c776.firebaseapp.com",
                    projectId: "nithitex-8c776",
                    storageBucket: "nithitex-8c776.appspot.com",
                    messagingSenderId: "41860351611",
                    appId: "1:41860351611:web:658657ff55604843bf04de",
                    measurementId: "G-06RSSGHG6F"
                };

                // Initialize Firebase app
                if (!firebase.apps.length) {
                    firebase.initializeApp(firebaseConfig);
                }
                const messaging = firebase.messaging();

                // Get FCM token
                messaging.getToken({
                        vapidKey: 'BEI3wF9xGdv1-Crh268bjMjVtjc2YqdDz4qG8M2RE4yp0bK9q93zoN2l2H-e5MyUPie9VSwSH9rQAJbUb2-SCHw'
                    })
                    .then((token) => {
                        if (token) {
                            console.log('FCM Token: ', token);
                            $('.fcm_token').val(token);
                        } else {
                            console.warn('No FCM token available.');
                        }
                    })
                    .catch((error) => {
                        console.error('Error getting FCM token:', error);
                    });
            })
            .catch((error) => {
                console.error('Service Worker registration failed:', error);
            });
    }
</script>
<script>
    $(document).ready(function() {
        FingerprintJS.load().then(fp => {
            fp.get().then(result => {
                var device_id = result.visitorId;
                $('.device_id').val(device_id);
            });

        });
    });
</script>

@endsection
