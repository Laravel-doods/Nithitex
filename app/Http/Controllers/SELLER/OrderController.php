<?php

namespace App\Http\Controllers\SELLER;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use PDF;

class OrderController extends Controller
{
    public function MyOrders()
    {

        $orders = Order::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
        return view('seller.dashboard.order_view', compact('orders'));
    }
    public function OrderDetails($order_id)
    {

        $order = Order::with('seller')->where('id', $order_id)->where('user_id', Auth::user()->id)->first();
        $orderItem = OrderItem::with('product')->where('order_id', $order_id)->orderBy('id', 'DESC')->get();
        return view('seller.dashboard.order_details', compact('order', 'orderItem'));
    } // end mehtod 

    public function InvoiceDownload($order_id)
    {
        $order = Order::with('seller')->where('id', $order_id)->where('user_id', Auth::user()->id)->first();
        $orderItem = OrderItem::with('product')->where('order_id', $order_id)->orderBy('id', 'DESC')->get();
        $pdf = PDF::loadView('seller.dashboard.order_invoice', compact('order', 'orderItem'))->setPaper('a4')->setOptions([
            'tempDir' => public_path(),
            'chroot' => public_path(),
        ]);
        return $pdf->download('invoice.pdf');
    }

    public function OrderTraking(Request $request)
    {

        $track_no = $request->code;

        $track = Order::where('track_number', $track_no)->first();

        if ($track) {

            return view('seller.traking.track_order', compact('track'));
        } else {

            $notification = array(
                'message' => 'Invalid Track Number',
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification);
        }
    } // end mehtod


    public function ReturnOrder(Request $request, $order_id)
    {
        $userorder = Order::join('users', 'users.id', 'orders.user_id')->where('orders.id', $order_id)->select('users.phone', 'users.email', 'users.name')->first();

        Order::findOrFail($order_id)->update([
            'return_date' => Carbon::now()->format('d F Y'),
            'return_reason' => $request->return_reason,
            'return_order' => 1,
        ]);


        $notification = array(
            'message' => 'Return Request Send Successfully',
            'alert-type' => 'success'
        );

        Http::post('http://pay4sms.in/sendsms/?token=9a2edf41bc2760c4c5bb0b592eaf7bfb&credit=2&sender=NITHTX&message=Dear ' . $userorder->name . ', Your return request has been submitted successfully. Our executive will contact you soon. Regards - NITHTX&number=' . $userorder->phone . '');

        return redirect()->route('seller.my.orders')->with($notification);
    } // end method 

    public function ReturnOrderList()
    {
        $orders = Order::where('user_id', Auth::id())->where('return_reason', '!=', NULL)->orderBy('id', 'DESC')->get();
        return view('seller.dashboard.return_order_view', compact('orders'));
    } // end method 
    public function CancelOrders()
    {

        $orders = Order::where('user_id', Auth::id())->where('status', '6')->orderBy('id', 'DESC')->get();
        return view('seller.dashboard.cancel_order_view', compact('orders'));
    }

    public function sellerCancelRequest(Request $request, $order_id)
    {
        $userorder = Order::join('users', 'users.id', 'orders.user_id')->where('orders.id', $order_id)->select('users.phone', 'users.email', 'users.name')->first();

        Order::findOrFail($order_id)->update([
            'cancel_date' => Carbon::now()->format('d F Y'),
            'cancel_request' => 1,
        ]);

        $notification = array(
            'message' => 'Cancel Request Sent Successfully',
            'alert-type' => 'success'
        );

        Http::post('http://pay4sms.in/sendsms/?token=9a2edf41bc2760c4c5bb0b592eaf7bfb&credit=2&sender=NITHTX&message=Dear Customer, We have received your request regarding order cancellation and the order has been cancel successfully Regards - NITHTX&number=' . $userorder->phone . '');


        return redirect()->route('seller.my.orders')->with($notification);
    }
}
