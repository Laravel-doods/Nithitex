<footer class="footer-area pl-40 pt-30 pb-30 pr-40">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-3 col-xs-3">
                    <div class="footer-logo">
                        <a href="{{route('seller.home')}}">
                          <img src="{{ asset('frontend/assets/images/logo/logo1.png')}}" class="img-responsive" alt="logo">
                        </a>
                    </div>
                    @php 
                      $footer = App\Models\ShopInformation::find(1);
                    @endphp
                    <div class="single-contact-info pt-15 pb-15">
                        <h4>Our Location</h4>
                        <p>{{$footer->address_line_1}} <br>{{$footer->address_line_2}} {{$footer->pincode}}</p>
                      </div>
                      <div class="single-contact-info pt-15 pb-15">
                        <h4>24/7 hotline:</h4>
                        <p>{{$footer->mobile_number}}</p>
                      </div>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-2 col-xs-2 pt-50">
                  <div class="footer-menu footer-app">
                    <h4>Download App</h4>
                      <div class="footer-logo-google">
                        <a href="https://play.google.com/store/apps/details?id=com.nithitex.app"><img src="{{ asset('frontend/assets/images/logo/google-paly.png')}}" class="img-responsive" alt="logo"></a>
                      </div>
                      <div class="footer-logo-google pt-5">
                        <a href="https://apps.apple.com/in/app/nithitex/id6463396746"><img src="{{ asset('frontend/assets/images/logo/app-store.png')}}" class="img-responsive" alt="logo"></a>
                      </div>
                  </div>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-2 col-xs-2 pt-50">
                    <div class="footer-menu">
                        <h4>Quick Links</h4>
                          <ul>
                            <li>
                              <a href="{{route('seller.home')}}">Home</a>
                            </li>
                            <li>
                              <a href="{{ route('seller.about') }}">About us</a>
                            </li>
                            <li>
                              <a href="{{ route('seller.product.shop') }}">Shop</a>
                            </li>
                            <li>
                              <a href="{{ route('seller.contact') }}">Contact</a>
                            </li>
                            <li>
                              <a href="{{ route('seller.terms') }}">Terms & Conditions</a>
                            </li>
                            <li>
                              <a href="{{ route('seller.privacy') }}">Privacy Policy</a>
                            </li>
                            <li>
                              <a href="{{ route('seller.return.policy') }}">Return Policy</a>
                            </li>
                            <li>
                              <a href="{{ route('seller.support') }}">Support Policy</a>
                            </li>
                          </ul>
                      </div>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-2 col-xs-2 pt-50">
                  <div class="footer-menu">
                      <h4>My Account</h4>
                        <ul>
                          <li>
                            <a href="{{route('seller.dashboard')}}">My Account</a>
                          </li>
                          <li>
                            <a href="{{ route('seller.product.shop') }}">Shop</a>
                          </li>
                          <li>
                            <a href="{{route('seller.mycart')}}">Cart</a>
                          </li>
                          <li>
                            <a href="{{route('seller.wishlist')}}">Wishlist</a>
                          </li>
                          <li>
                            <a href="{{ route('seller.track_order') }}">Track Order</a>
                          </li>
                        </ul>
                    </div>
              </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-xs-3 footer-menu pt-50">
                    <h4>Connect with us</h4>
                    <div class="footer-right-wrap">
                        <div class="">
                            <a href="{{$footer->facebook}}" target="_blank">
                              <img src="{{ asset('frontend/assets/images/logo/Facebook-logo.png') }}" class="img-responsive" title="Facebook" alt="" >
                            </a>
                            <a href="{{$footer->twitter}}" target="_blank">
                              <img src="{{ asset('frontend/assets/images/logo/twitter-logo-png.png') }}" class="img-responsive" title="Twitter" alt="">
                            </a>
                            <a href="{{$footer->instagram}}" target="_blank">
                              <img src="{{ asset('frontend/assets/images/logo/new-Instagram-logo.png') }}" class="img-responsive" title="Instagram" alt="">
                            </a>
                            <a href="{{$footer->youtube}}" target="_blank">
                              <img src="{{ asset('frontend/assets/images/logo/youtube-logo-png.png') }}" class="img-responsive" title="YouTube" alt="">
                            </a>
                          </div>
                      </div>
                </div>
            </div>
        </div>
      </footer>
      <div class="copyright">
        <p>Copyright © 2023 <span><a href="/">Nithi Tex</a></span> | All Rights Reserved. </p>
      </div>
