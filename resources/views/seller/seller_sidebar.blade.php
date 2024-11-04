@php
    $id = Auth::user()->id;
    $seller = App\Models\Seller::find($id);
@endphp


<div class="col-lg-2 col-md-2">
	<div class="myaccount-tab-menu nav">
		<a href="{{ route('seller.dashboard') }}"><i class="fa fa-dashboard"></i>
			Dashboard</a>
		<a href="{{ route('seller.profile') }}"><i class="fa fa-user"></i> Profile Update </a>
		<a href="{{ route('seller.password') }}"><i class="fa fa-unlock-alt"></i> Change Password </a>
		<a href="{{ route('seller.orders') }}" ><i class="fa fa-cart-arrow-down"></i> Orders </a>
		<a href="{{ route('seller.return') }}" ><i class="fa fa-exchange"></i> Return Orders </a>
		<a href="{{ route('seller.cancel') }}"><i class="fa fa-ban"></i> Cancel Orders </a>
		<a href="{{ route('user.logout') }}"><i class="fa fa-sign-out"></i> Logout </a>
	</div>
</div>
