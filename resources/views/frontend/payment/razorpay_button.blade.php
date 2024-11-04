<button id="rzp-button1" hidden>Pay</button>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    var options = {
        "key": "{{ $response['razorpayId'] }}", // Enter the Key ID generated from the Dashboard
        "amount": "{{ $response['amount'] }}", // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
        "currency": "INR",
        "name": "India's No 1 Online Saree Shop",
        "description": "India's No 1 Online Saree Shop",
        "image": "https://www.nithitex.com/public/frontend/assets/images/app_logo.png",
        "order_id": "{{ $response['order_id'] }}", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
        "handler": function(response) {
            //  alert(response.razorpay_payment_id);
            //  alert(response.razorpay_order_id);
            //  alert(response.razorpay_signature);
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
            document.getElementById('razorpay_signature').value = response.razorpay_signature;


            document.getElementById('razorpay_submit').click();

        },
        // "callback_url": "https://eneqd3r9zrjok.x.pipedream.net/",

        "prefill": {
            "name": "{{ $response['name'] }}",
            "email": "{{ $response['email'] }}",
            "contact": "{{ $response['phone'] }}"
        },
        "notes": {
            "address": "Razorpay Corporate Office"
        },
        "theme": {
            "color": "#3399cc"
        },
        "modal": {
            "escape": "false",
            "ondismiss": function() {
                // window.location.href = 'http://localhost:8000/paymentstatus';
                window.location.href='https://www.nithitex.com/paymentstatus';
                // window.location.href='http://nithitex.adhocerp.in/paymentstatus';

            }
        }
    };
    var rzp1 = new Razorpay(options);
    // rzp1.on('payment.failed', function (response){
    //     alert(response.error.code);
    //     alert(response.error.description);
    //     alert(response.error.source);
    //     alert(response.error.step);
    //     alert(response.error.reason);
    //     alert(response.error.metadata.order_id);
    //     alert(response.error.metadata.payment_id);
    //     $("#info").html(response.error.code);
    //     $("#homelink").style("display","block");

    // });
    window.onload = function() {
        document.getElementById('rzp-button1').click();

    };
    document.getElementById('rzp-button1').onclick = function(e) {
        rzp1.open();
        e.preventDefault();
    }
</script>
<form action="{{ route('razorpay.complete') }}" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
    <input type="hidden" name="razorpay_signature" id="razorpay_signature">
    <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
    <input type="hidden" name="cart_true" value="{{ $data['cart_true'] }}">
    <input type="hidden" name="buy_now_product_name" value="{{ $data['buy_now_product_name'] }}">
    <input type="hidden" name="buy_now_product_qty" value="{{ $data['buy_now_product_qty'] }}">
    <input type="hidden" name="buy_now_product_id" value="{{ $data['buy_now_product_id'] }}">
    <input type="hidden" name="buy_now_variant_id" value="{{ $data['buy_now_variant_id'] }}">
    <input type="hidden" name="buy_now_price" value="{{ $data['buy_now_price'] }}">
    <input type="hidden" name="amount" value="{{ $response['amount'] }}">
    <input type="hidden" name="sub_total" value="{{ $data['sub_total'] }}">
    <input type="hidden" name="coupon_id" value="{{ $data['coupon_id'] }}">
    <input type="hidden" name="coupon_discount" value="{{ $data['coupon_discount'] }}">
    <input type="hidden" name="shipping_charge" value="{{ $data['shipping_charge'] }}">
    <input type="hidden" name="name" value="{{ $data['shipping_name'] }}">
    <input type="hidden" name="email" value="{{ $data['shipping_email'] }}">
    <input type="hidden" name="phone" value="{{ $data['shipping_phone'] }}">
    <input type="hidden" name="alternative_number" value="{{ $data['alternative_number'] }}">
    <input type="hidden" name="door_no" value="{{ $data['door_no'] }}">
    <input type="hidden" name="street_address" value="{{ $data['street_address'] }}">
    <input type="hidden" name="city_name" value="{{ $data['city_name'] }}">
    <input type="hidden" name="state_name" value="{{ $data['state_name'] }}">
    <input type="hidden" name="pin_code" value="{{ $data['pin_code'] }}">
    <input type="hidden" name="order_id" value="{{ $data['order_id'] }}">
    <button type="submit" name="razorpay_submit" id="razorpay_submit" class="btn btn-primary">
    </button>
</form>
<div style="width:100%; height:500px; text-align:center;">
    <div
        style="margin: auto;width: 50%;border: 3px solid green;padding: 70px 0;position: absolute;top: 25%;left: 25%;font-family: sans-serif;font-size: 20px;">
        <div id="info">Please wait! We are processing payment. <br> Do not press back or refresh button during
            payment process.</div>

    </div>
</div>
