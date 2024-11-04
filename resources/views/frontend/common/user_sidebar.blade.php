@php
    $id = Auth::user()->id;
    $user = App\Models\User::find($id);
@endphp


<div class="col-lg-2 col-md-2">
	<div class="myaccount-tab-menu nav">
		<a href="{{ route('user.dashboard') }}" class="active" ><i class="fa fa-dashboard"></i>
			Dashboard</a>
		<a href="{{ route('user.profile') }}"><i class="fa fa-user"></i> Profile Update</a>
		<a href="{{ route('change.password') }}"><i class="fa fa-unlock-alt"></i> Change Password</a>
		<a href="{{ route('my.orders') }}" ><i class="fa fa-cart-arrow-down"></i> Orders</a>
		<a href="{{ route('return.order.list') }}" ><i class="fa fa-exchange"></i> Return Orders</a>
		<a href="{{ route('cancel.orders') }}"><i class="fa fa-ban"></i> Cancel Orders</a>
		<a href="{{ route('user.logout') }}"><i class="fa fa-sign-out"></i> Logout</a>
	</div>
</div>