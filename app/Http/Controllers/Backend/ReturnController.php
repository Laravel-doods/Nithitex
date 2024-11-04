<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariants;
use App\Traits\Utils;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ReturnController extends Controller
{
    use Utils;
    public function ReturnRequest()
    {
        $orders = Order::with('user', 'seller')->where('return_order', 1)->where('userrole_id', 1)->orderBy('id', 'DESC')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Customer Return Request'), Auth::user()->id)) {
            return view('backend.return_order.return_request', compact('orders'));
        } else {
            return view('401');
        }
    }

    public function sellerReturnRequest()
    {
        $orders = Order::with('user', 'seller')->where('return_order', 1)->where('userrole_id', 2)->orderBy('id', 'DESC')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Reseller Return Request'), Auth::user()->id)) {
            return view('backend.return_order.seller_return_request', compact('orders'));
        } else {
            return view('401');
        }
    }

    public function cancelRequest()
    {
        $orders = Order::with('user', 'seller')->where('cancel_request', 1)->where('userrole_id', 1)->orderBy('id', 'DESC')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Customer Cancel Request'), Auth::user()->id)) {
            return view('backend.return_order.cancel_request', compact('orders'));
        } else {
            return view('401');
        }
    }

    public function sellerCancelRequest()
    {
        $orders = Order::with('user', 'seller')->where('cancel_request', 1)->where('userrole_id', 2)->orderBy('id', 'DESC')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Reseller Cancel Request'), Auth::user()->id)) {
            return view('backend.return_order.seller_cancel_request', compact('orders'));
        } else {
            return view('401');
        }
    }

    public function ReturnRequestApprove($order_id)
    {
        Order::where('id', $order_id)->update([
            'return_order' => 2,
            'status' => 'returned'
        ]);

        $otderItem = OrderItem::where('order_id', $order_id)->get();

        foreach ($otderItem as $item) {
            $update_stock = Product::where('id', $item->product_id)->first();
            $update_stock->current_stock = $update_stock->current_stock + $item->qty;
            $update_stock->update();
            if($item->variant_size != null){
                $variant = ProductVariants::where('product_id', $item->product_id)->where('size', $item->variant_size)->first();
                $variant->stock = $variant->stock + $item->qty; 
                $variant->update();
            }
        }

        $notification = array(
            'message' => 'Order Returned Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // end mehtod 

    public function ReturnRequestReject($order_id)
    {
        Order::where('id', $order_id)->update([
            'return_order' => 3
        ]);

        $notification = array(
            'message' => 'Order Rejected Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function sellerReturnRequestApprove($order_id)
    {
        Order::where('id', $order_id)->update([
            'return_order' => 2,
            'status' => 'returned'
        ]);
        
        $otderItem = OrderItem::where('order_id', $order_id)->get();

        foreach ($otderItem as $item) {
            $update_stock = Product::where('id', $item->product_id)->first();
            $update_stock->current_stock = $update_stock->current_stock + $item->qty;
            $update_stock->update();
            if($item->variant_size != null){
                $variant = ProductVariants::where('product_id', $item->product_id)->where('size', $item->variant_size)->first();
                $variant->stock = $variant->stock + $item->qty; 
                $variant->update();
            }
        }

        $notification = array(
            'message' => 'Order Returned Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function sellerReturnRequestReject($order_id)
    {
        Order::where('id', $order_id)->update([
            'return_order' => 3
        ]);

        $notification = array(
            'message' => 'Order Rejected Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function cancelRequestApprove($order_id)
    {
        $this->approveCancelRequest($order_id, 0);
        $notification = array(
            'message' => 'Order Cancelled Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function sellerCancelRequestApprove($order_id)
    {
        $this->approveCancelRequest($order_id, 1);
        $notification = array(
            'message' => 'Order Cancelled Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    private function approveCancelRequest($order_id, $isSeller)
    {

        $userorder = Order::join('users', 'users.id', 'orders.user_id')->where('orders.id', $order_id)->select('users.phone', 'users.email', 'users.name')->first();

        if ($isSeller == 0) {
            $mobile = Order::where('id', $order_id)->pluck('phone')->first();
        } else {
            $mobile = $userorder->phone;
        }


        Order::where('id', $order_id)->update([
            'cancel_request' => 2,
            'status' => 'cancelled'
        ]);
        
        $otderItem = OrderItem::where('order_id', $order_id)->get();

        foreach ($otderItem as $item) {
            $update_stock = Product::where('id', $item->product_id)->first();
            $update_stock->current_stock = $update_stock->current_stock + $item->qty;
            $update_stock->update();
            if($item->variant_size != null){
                $variant = ProductVariants::where('product_id', $item->product_id)->where('size', $item->variant_size)->first();
                $variant->stock = $variant->stock + $item->qty; 
                $variant->update();
            }
        }

        Http::post('http://pay4sms.in/sendsms/?token=9a2edf41bc2760c4c5bb0b592eaf7bfb&credit=2&sender=NITHTX&message=Your order has been Cancelled.Check your status here: https://nithitex.com/track-your-order ,Nithitex.	&number=' . $mobile . '');
    }
    public function ReturnAllRequest()
    {

        $orders = Order::with('user', 'seller')->where('return_order', 2)->orwhere('return_order', 3)->orderBy('id', 'DESC')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Return Orders List'), Auth::user()->id)) {
            return view('backend.return_order.all_return_request', compact('orders'));
        } else {
            return view('401');
        }
    }
    public function cancelAllRequest()
    {
        $orders = Order::with('user', 'seller')->where('cancel_request', 2)->orderBy('id', 'DESC')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Cancel Orders List'), Auth::user()->id)) {
            return view('backend.return_order.all_cancel_request', compact('orders'));
        } else {
            return view('401');
        }
    }
}
