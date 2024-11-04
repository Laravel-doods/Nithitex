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
Contact | India's No 1 Online Saree Shop - Nithitex
@endsection

<div class="breadcrumb-area bg-gray">
  <div class="container">
    <div class="breadcrumb-content text-center">
      <ul>
        <li>
          <a href="/">Home</a>
        </li>
        <li class="active">
          <a>Contact us</a>
        </li>
      </ul>
    </div>
  </div>
</div>
<div class="contact-area-touch pt-50 pb-50">
  <div class="container">
    <div class="contact-home contact-info-wrap-3">
      <h3>Get In Touch</h3>
      <div class="row">
        @foreach ($contact as $shopInformation )
        <div class="col-lg-6 col-md-6">
          <img src="{{ asset($shopInformation->contact_image) }}" class="img-fluid" alt="">
        </div>
        <div class="col-lg-6 col-md-6">
          <div class="col-lg-12 col-md-12 pb-12">
            <div class="single-contact-info-3 mb-30">
              <ul>
                <li>
                  <i class="icon-location-pin "></i>
                </li>
              </ul>
              <h4>Location</h4>
              <p>{{$shopInformation->address_line_1}} <br>{{$shopInformation->address_line_2}} {{$shopInformation->pincode}}</p>
            </div>
          </div>
          <div class="col-lg-12 col-md-12 pt-12">
            <div class="single-contact-info-3 extra-contact-info mb-30">
              <h4>Contact</h4>
              <ul>
                <li>
                  <i class="icon-screen-smartphone"></i> {{$shopInformation->mobile_number}}
                </li>
                <li>
                  <i class="icon-envelope "></i>
                  <a href="#">{{$shopInformation->email}}</a>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-lg-12 col-md-12">
            <div class="single-contact-info-3 mb-30">
              <ul>
                <li>
                  <i class="icon-clock "></i>
                </li>
              </ul>
              <h4>openning hours</h4>
              <p>Monday - Saturday. 9:00am - 6:00pm </p>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
<div class="contact-area-contact pt-50 pb-15">
  <div class="container-fluid">
    <div class="contact-home contact-info-wrap-3">
      <h3>Our Location</h3>
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-xs-12">
          <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15633.019502233423!2d78.0043741!3d11.6051696!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xe2c8e250a360799!2sNithi%20Tex!5e0!3m2!1sen!2sin!4v1667043910833!5m2!1sen!2sin" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection