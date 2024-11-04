@extends('frontend.main_master')
@section('content')
@section('title')
Checkout | Online Payment - Razorpay | India's No 1 Online Saree Shop - Nithitex
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="/">Checkout</a>
                </li>
                <li class="active">Online Payment - Razorpay</li>
            </ul>
        </div>
    </div>
</div>

<div class="about-us-area pt-120 pb-120">
    <div class="container">
        <div class="row">
				<div class="col-lg-4 col-md-4">
					<div class="your-order-area">
						<h3>Summary</h3>
						<div class="your-order-wrap gray-bg-4">
							<div class="your-order-info-wrap">
								<div class="your-order-info">
									<ul>
										<li>Product <span>Total</span></li>
									</ul>
								</div>
								@foreach($carts as $item)
								<div class="your-order-middle">
									<ul>
										<li>{{ $item->name }}  X   {{ $item->qty }}  <span>₹{{ round($item->price) }} </span></li>
									</ul>
								</div>
								@endforeach 
								<div class="your-order-info order-subtotal">
									<ul>
										<li>Subtotal <span>₹{{ round($cartTotal) }} </span></li>
									</ul>
								</div>
								<div class="your-order-info order-shipping">
									<ul><li>Shipping <p> Free Shipping</p></li></ul>
								</div>
								<div class="your-order-info order-total">
									<ul>
										<li>Total <span>₹{{ round($cartTotal) }} </span></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-8 col-md-8">
					<form action="{{ route('razorpay.order') }}" method="POST">
						@csrf
						<div class="col d-flex justify-content-center align-items-center">
							<img src="{{ asset('frontend/assets/images/logo/razorpay.png') }}" class="img-responsive" style="width: 300px; height: 200px;" alt="">
						</div>
					<div class="Place-order d-flex justify-content-center align-items-center mt-20 floar-right">
						<input type="hidden" name="amount" value="{{ $data['total_amount'] }}">
						<input type="hidden" name="name" value="{{ $data['shipping_name'] }}">
						<input type="hidden" name="email" value="{{ $data['shipping_email'] }}">
						<input type="hidden" name="phone" value="{{ $data['shipping_phone'] }}">
						<input type="hidden" name="alternative_number" value="{{ $data['alternative_number'] }}">
						<input type="hidden" name="door_no" value="{{ $data['door_no'] }}">
						<input type="hidden" name="street_address" value="{{ $data['street_address'] }}">
						<input type="hidden" name="city_name" value="{{ $data['city_name'] }}">
						<input type="hidden" name="state_name" value="{{ $data['state_name'] }}">
						<input type="hidden" name="pin_code" value="{{ $data['pin_code'] }}"> 
						<button type="submit" class="btn btn-flat btn-dark">Confirm Order</button>
					</div>
			      </form>
				</div>
        </div>
    </div>
</div>

@endsection