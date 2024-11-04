<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Invoice</title>

    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }

        table {
            font-size: x-small;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        .gray {
            background-color: lightgray
        }

        .font {
            font-size: 15px;
        }

        .authority {
            float: right
        }

        .authority h5 {
            margin-top: -10px;
            color: green;
            margin-left: 35px;
        }

        .thanks p {
            color: green;
            ;
            font-size: 16px;
            font-weight: normal;
            font-family: serif;
            margin-top: 20px;
        }
    </style>

</head>

<body>
    <h4 style="text-align: center !important; color: green;">Nithi Tex | india's No 1 Online Saree Shop</h4>
    <table width="100%" style="background: #F7F7F7; padding:0 5 0 5px;" class="font">
        <tr>
            <td width="60%">
                <img src="{{ public_path('frontend/assets/images/logo/nithitex-logo-large.png') }}"
                    class="img-responsive" alt="" width="80%" />
            </td>
            <td width="40%">
                @php
                    $footer = App\Models\ShopInformation::find(1);
                @endphp
                <p class="font" style="text-align:justify;">
                    <b>Nithi Tex Head Office</b><br>
                    Email:{{ $footer->email }},<br>
                    Ph: 04272910279,<br>
                    Mob: +91 7092957279,<br>
                    {{ $footer->address_line_1 }}<br>
                    {{ $footer->address_line_2 }} {{ $footer->pincode }}
                </p>
            </td>
        </tr>
    </table>


    <table width="100%" style="background:white; padding:2px;"></table>
    <table width="100%" style="background: #F7F7F7; padding:0 5 0 5px;" class="font">
        <tr>
            <td>
                <p class="font" style="margin-left: 20px;">
                    <strong>Name:</strong> {{ $order->name }}<br>
                    <strong>Phone:</strong> {{ $order->phone }} <br>
                    @if ($order->alternative_number)
                        <strong>Alternative Number:</strong> {{ $order->alternative_number }} <br>
                    @endif
                    <strong>Address:</strong> {{ $order->door_no }},{{ $order->street_address }}<br>
                    {{ $order->city_name }}.{{ $order->state_name }} <br>
                    <strong>Post Code:</strong> {{ $order->pin_code }}
                </p>
            </td>
            <td>
                <p class="font">
                <h3><span style="color: green;">Invoice:</span> #{{ $order->invoice_no }}</h3>
                Order Date: {{ $order->order_date }} <br>
                Order No: {{ $order->order_number }} <br>
                Payment Type : {{ $order->payment_type }} </span>
                </p>
            </td>
        </tr>
    </table>
    <br />
    <h3>Products</h3>
    <table width="100%">
        <thead style="background-color: green; color:#FFFFFF;">
            <tr class="font">
                <th>Image</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price </th>
                <th>Total </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orderItem as $item)
                <tr class="font">
                    <td align="center">
                        <img src="{{ public_path($item->product->product_image) }}" height="60px;" width="60px;"
                            alt="">
                    </td>
                    <td align="center">{{ $item->product->product_name }}</td>
                    <td align="center">{{ $item->qty }}</td>
                    <td align="center"><span
                            style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{ $item->price / $item->qty }}
                    </td>
                    <td align="center"><span
                            style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{ $item->price }} </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <table width="100%" style=" padding:0 10px 0 10px;">
        <tr>
            <td align="right">
                <h2><span style="color: green;">Subtotal:</span><span
                        style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{ round($order->sub_total) }}</h2>
                @if ($order->coupon_discount)
                    <h2><span style="color: green;">Discount ({{ $order->couponCode->discount_percentage }}%):
                        </span><span
                            style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{ round($order->coupon_discount) }}
                    </h2>
                @endif
                <h2><span style="color: green;">Shipping Charge:</span><span
                        style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{ round($order->shipping_charge) }}
                </h2>
                @if ($order->payment_type == 'Cash On Delivery')
                    <h2><span style="color: green;">Margin Amount:</span><span
                            style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{ round($order->margin_amount) }}
                    </h2>
                @endif
                <h2><span style="color: green;">Total:</span><span
                        style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{ round($order->amount) }}</h2>
            </td>
        </tr>
    </table>
    <div class="thanks mt-3">
        <p>Thanks For Buying Products..!!</p>
    </div>
</body>

</html>
