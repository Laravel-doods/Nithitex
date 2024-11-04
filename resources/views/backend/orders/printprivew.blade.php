<div class="row">
    <div class="col-lg-6">
        <h2>From</h2>
        <h2 class="pl-5">{{$order->user->name}}<br>
                {{$order->user->phone}}</h2>
    </div>  
    <div class="col-lg-6">
         <h1>To</h1>
        <h2>{{$order->name}},<br>
        {{$order->door_no}},
        {{$order->street_address}},<br> 
        {{$order->city_name}},<br>
        {{$order->state_name}},<br>
        {{$order->pin_code}},<br>
        {{$order->phone}}<br>@if($order->alternative_number){{$order->alternative_number}} @endif
    </h2>                    
    </div>       
</div>

