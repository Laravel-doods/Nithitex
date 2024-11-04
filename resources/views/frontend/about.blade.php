@php
$url = "frontend.main_master";
$seller_url = "";
if(Auth::check()) {
    if (Auth::user()->userrole_id == 2) {
        $url = "seller.seller_main_master";
        $seller_url = "seller.";
    }
}
@endphp
@extends($url)
@section('content')
@section('title')
About | India's No 1 Online Saree Shop - Nithitex
@endsection

<div class="breadcrumb-area bg-gray">
  <div class="container">
      <div class="breadcrumb-content text-center">
          <ul>
              <li>
                  <a href="/">Home</a>
              </li>
              <li class="active">
                <a>About us</a>
              </li>
          </ul>
      </div>
  </div>
</div>
<div class="about-us-area pt-50 pb-50">
  <div class="container">
      <div class="row">
        @foreach ($about as $abt)
            
          <div class="col-lg-3 col-md-3">
              <div class="about-us-logo">
                <img src="{{ asset($abt->about_image) }}" class="img-fluid" alt="logo">
              </div>
          </div>
          <div class="col-lg-9 col-md-9">
              <div class="about-us-content">
                  <h3>Introduce</h3>
                  <p class="pt-10 pb-10 pl-20">{!!$abt->about_description!!}</p>
                  <div class="signature hidden">
                      <h2>David Moye</h2>
                  </div>
              </div>
          </div>
        @endforeach
      </div>
  </div>
</div>
<div class="service-area pb-120">
  <div class="container">
      <div class="service-wrap-border service-wrap-padding-3">
          <div class="row">
              <div class="col-lg-3 col-md-6 col-sm-6 col-12 service-border-1">
                  <div class="single-service-wrap-2 mb-30">
                      <div class="service-icon-2 icon-red">
                          <i class="icon-cursor"></i>
                      </div>
                      <div class="service-content-2">
                          <h3>Global Shipping</h3>
                          <p>Available in all over the world</p>
                      </div>
                  </div>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-6 col-12 service-border-1 service-border-1-none-md">
                  <div class="single-service-wrap-2 mb-30">
                      <div class="service-icon-2 icon-red">
                          <i class="icon-refresh "></i>
                      </div>
                      <div class="service-content-2">
                          <h3>3 Days Return</h3>
                          <p>For any problems</p>
                      </div>
                  </div>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-6 col-12 service-border-1">
                  <div class="single-service-wrap-2 mb-30">
                      <div class="service-icon-2 icon-red">
                          <i class="icon-credit-card "></i>
                      </div>
                      <div class="service-content-2">
                          <h3>Secure Payment</h3>
                          <p>100% Guarantee</p>
                      </div>
                  </div>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                  <div class="single-service-wrap-2 mb-30">
                      <div class="service-icon-2 icon-red">
                          <i class="icon-earphones "></i>
                      </div>
                      <div class="service-content-2">
                          <h3>24<small>x</small>7 Support</h3>
                          <p>Dedicated support</p>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>

<div class="testimonial-area bg-gray-3 pt-115 pb-115 hidden">
  <div class="container">
      <div class="section-title mb-45 text-center">
          <h2>Testimonials</h2>
      </div>
      <div class="testimonial-active-2 dot-style-2 dot-style-2-position-static">
          <div class="single-testimonial-2 text-center">
              <div class="testimonial-img">
                  <img alt="" src="{{ asset('frontend/assets/images/testimonial/client-1.png') }}">
              </div>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo.</p>
              <div class="client-info">
                  <h5>Anna Miller</h5>
                  <span>Deginer</span>
              </div>
          </div>
          <div class="single-testimonial-2 text-center">
              <div class="testimonial-img">
                  <img alt="" src="{{ asset('frontend/assets/images/testimonial/client-1.png') }}">
              </div>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim, quis nostrud exercitation ullamco laboris nisi ut aliquip commodo.</p>
              <div class="client-info">
                  <h5>Anna Miller</h5>
                  <span>Deginer</span>
              </div>
          </div>
      </div>
  </div>
</div>

@endsection