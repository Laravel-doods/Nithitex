<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\OrderMail;
use App\Models\Product;
use App\Models\ProductVariants;
use App\Models\UserReferralHistory;
use App\Traits\Utils;
use Exception;
use Rap2hpoutre\FastExcel\FastExcel;

class OrderController extends Controller
{
    use Utils;

    public function orderView()
    {
        $orders = Order::with('user', 'qty')->where('userrole_id', 1)->orderBy('id', 'DESC')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Customer All Orders'), Auth::user()->id)) {
            return view('backend.orders.all_orders', compact('orders'));
        } else {
            return view('401');
        }
    } // end mehtod 

    public function getAllCustomerOrders()
    {
        $allCustomerOrder = Order::select('orders.*', 'users.name')
            ->leftJoin('users', 'orders.user_id', 'users.id')
            ->orderBy('orders.id', 'DESC')
            ->where('orders.userrole_id', 1)
            ->get();
        return datatables()->of($allCustomerOrder)
            ->addColumn('action', function ($row) {
                $html = '<a href="' . route('order.details', $row->id) . '" class="btn btn-info" title="View Details"><i class="fa fa-eye"></i> Update</a>';
                return $html;
            })
            ->toJson();
    }

    public function sellerOrderView()
    {
        $sellerorders = Order::with('seller', 'qty', 'user')->where('userrole_id', 2)->orderBy('id', 'DESC')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Reseller All Orders'), Auth::user()->id)) {
            return view('backend.orders.seller_all_orders', compact('sellerorders'));
        } else {
            return view('401');
        }
    }

    public function getAllSellerOrders(Request $request)
    {
        $query = Order::select('orders.*', 'users.name as userName', 'users.phone as userphone', 'users.city_name as usercity', 'users.state_name as userstate')
            ->leftJoin('users', 'orders.user_id', 'users.id')
            ->where('orders.userrole_id', 2)
            ->orderBy('orders.id', 'DESC');


        if ($request->has('datefilter') && !empty($request->datefilter)) {
            $query->whereDate('orders.created_at', $request->datefilter);
        }
        $allSellerOrder = $query->get();

        return datatables()->of($allSellerOrder)
            ->addColumn('action', function ($row) {
                $html = '<a href="' . route('seller.order.details', $row->id) . '" class="btn btn-info" title="View Details"><i class="fa fa-eye"></i> Update</a>';
                return $html;
            })
            ->toJson();
    }



    // Pending Order Details 
    public function OrdersDetails($order_id)
    {

        $order = Order::with('user', 'qty', 'couponCode')->where('id', $order_id)->first();
        $orderItem = OrderItem::with('product')->where('order_id', $order_id)->orderBy('id', 'DESC')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Customer All Orders'), Auth::user()->id)) {
            return view('backend.orders.orders_details', compact('order', 'orderItem'));
        } else {
            return view('401');
        }
    } // end method 

    public function sellerOrdersDetails($order_id)
    {
        $order = Order::with('seller', 'qty', 'user')->where('id', $order_id)->first();
        $orderItem = OrderItem::with('product')->where('order_id', $order_id)->orderBy('id', 'DESC')->get();

        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Reseller All Orders'), Auth::user()->id)) {
            return view('backend.orders.seller_orders_details', compact('order', 'orderItem'));
        } else {
            return view('401');
        }
    }

    public function CustomerOrderStatusUpdate(Request $request)
    {
        $this->updateOrderStatus($request, 0);
        $notification = array(
            'message' => 'Order Status Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // end mehtod 

    public function sellerOrderStatusUpdate(Request $request)
    {
        $this->updateOrderStatus($request, 1);

        $notification = array(
            'message' => 'Order Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    private function updateOrderStatus(Request $request, $isSeller)
    {
        $id = $request->order_id;
        $userorder = Order::join('users', 'users.id', 'orders.user_id')->where('orders.id', $id)->select('orders.*', 'users.phone', 'users.email', 'users.name')->first();

        if ($isSeller == 0) {
            $mobile = Order::where('id', $id)->pluck('phone')->first();
            $email = Order::where('id', $id)->pluck('email')->first();
            $username = Order::where('id', $id)->pluck('name')->first();
        } else {
            $mobile = $userorder->phone;
            $email = $userorder->email;
            $username = $userorder->name;
        }

        $payment_type = Order::where('id', $id)->pluck('payment_type')->first();

        if ($request->order_status == 'pending') {
            Http::post('http://pay4sms.in/sendsms/?token=9a2edf41bc2760c4c5bb0b592eaf7bfb&credit=2&sender=NITHTX&message=Your order is pending and will be Confirmed shortly. Check your status here: https://nithitex.com/track-your-order , Nithitex&number=' . $mobile . '');
        } elseif ($request->order_status == 'confirmed') {

            Order::findOrFail($id)->update([
                'confirmed_date' => Carbon::now(),
                'status' => $request->order_status,
            ]);

            Http::post('http://pay4sms.in/sendsms/?token=9a2edf41bc2760c4c5bb0b592eaf7bfb&credit=2&sender=NITHTX&message=Thanks for shopping with us! Your order is confirmed and will be shipped shortly. Check your status here: https://nithitex.com/track-your-order ,Nithitex.&number=' . $mobile . '');
        } elseif ($request->order_status == 'processing') {

            Order::findOrFail($id)->update([
                'processing_date' => Carbon::now(),
                'status' => $request->order_status,
            ]);
        } elseif ($request->order_status == 'picked') {

            Order::findOrFail($id)->update([
                'picked_date' => Carbon::now(),
                'status' => $request->order_status,
            ]);
        } elseif ($request->order_status == 'shipped') {

            $request->validate([
                'track_no' => 'required|unique:orders,track_number,' . $id,
            ]);

            Order::findOrFail($id)->update([
                'shipped_date' => Carbon::now(),
                'track_number' => $request->track_no,
                'track_url' => $request->track_url,
                'status' => $request->order_status,
            ]);

            if ($request->track_no) {
                $url = $request->track_url;
                Http::post('http://pay4sms.in/sendsms/?token=9a2edf41bc2760c4c5bb0b592eaf7bfb&credit=2&sender=NITHTX&message=Your Tracking ID is ' . $request->track_no . ' Tracking URK : ' . $url . ' Thanks, Regards - NITHTX&number=' . $mobile . '');
            }

            Http::post('http://pay4sms.in/sendsms/?token=9a2edf41bc2760c4c5bb0b592eaf7bfb&credit=2&sender=NITHTX&message=Hi ' . $username . ', your package has now been shipped. View tracking details : https://nithitex.com/track-your-order , Nithitex.&number=' . $mobile . '');
        } elseif ($request->order_status == 'delivered') {
            $orderCount = Order::where('status', 'delivered')->where('user_id', $userorder->user_id)->count();
            if ($userorder->delivered_date == null) {
                $userReferralHis = UserReferralHistory::where('user_id', $userorder->user_id)->first();
                if ($orderCount == 0) {
                    $this->addReferrerPoints($userReferralHis->referred_by);
                }
            }

            Order::findOrFail($id)->update([
                'delivered_date' => Carbon::now(),
                'status' => $request->order_status,
            ]);

            if ($payment_type == 'Cash On Delivery') {
                //  Start Send Email  

                Order::where('id', $id)->update([
                    'invoice_no' => 'NITHTX' . mt_rand(10000000, 99999999)
                ]);

                $order = Order::with('user')->where('id', $id)->first();
                $orderItem = OrderItem::with('product')->where('order_id', $id)->orderBy('id', 'DESC')->get();

                $pdf = PDF::loadView(($isSeller == 0 ? 'frontend.user.order.order_invoice' : 'seller.dashboard.order_invoice'), compact('order', 'orderItem'));
                $content = $pdf->download()->getOriginalContent();

                Mail::to($email)->send(new OrderMail($pdf, $order->invoice_no, 'cod'));
                
                $make_name = 'invoice/' . $order->invoice_no . '.pdf';
                $save_url = $this->fileUploadS3Bucket($make_name, $content, 1);

                Order::where('id', $order->id)->update([
                    'invoice' => $save_url
                ]);
            }

            Http::post('http://pay4sms.in/sendsms/?token=9a2edf41bc2760c4c5bb0b592eaf7bfb&credit=2&sender=NITHTX&message=Hi ' . $username . ', your order has now been delivered. View tracking details : https://nithitex.com/track-your-order , Nithitex.&number=' . $mobile . '');
        } elseif ($request->order_status == 'cancelled') {

            Order::findOrFail($id)->update([
                'cancel_date' => Carbon::now(),
                'status' => $request->order_status,
            ]);
            $otderItem = OrderItem::where('order_id', $id)->get();

            foreach ($otderItem as $item) {
                $update_stock = Product::where('id', $item->product_id)->first();
                $update_stock->current_stock = $update_stock->current_stock + $item->qty;
                $update_stock->update();
                if ($item->variant_size != null) {
                    $variant = ProductVariants::where('product_id', $item->product_id)->where('size', $item->variant_size)->first();
                    $variant->stock = $variant->stock + $item->qty;
                    $variant->update();
                }
            }

            Http::post('http://pay4sms.in/sendsms/?token=9a2edf41bc2760c4c5bb0b592eaf7bfb&credit=2&sender=NITHTX&message=Your order has been Cancelled.Check your status here: https://nithitex.com/track-your-order ,Nithitex.&number=' . $mobile . '');
        } elseif ($request->order_status == 'returned_to_office') {
            Order::findOrFail($id)->update([
                'returned_at_office' => Carbon::now(),
                'status' => $request->order_status,
            ]);
        }
    }

    public function PaymentunApprove($order_id)
    {

        Order::where('id', $order_id)->update([
            'payment_status' => "Unpaid"
        ]);

        $notification = array(
            'message' => 'Payment Unpaid Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // end mehtod 

    public function sellerPaymentunApprove($order_id)
    {

        Order::where('id', $order_id)->update([
            'payment_status' => "Unpaid"
        ]);

        $notification = array(
            'message' => 'Payment Unpaid Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
    public function PaymentpaidApprove($order_id)
    {

        Order::where('id', $order_id)->update([
            'payment_status' => "paid"
        ]);

        $notification = array(
            'message' => 'Payment Paid Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // end mehtod 

    public function sellerPaymentpaidApprove($order_id)
    {

        Order::where('id', $order_id)->update([
            'payment_status' => "paid"
        ]);

        $notification = array(
            'message' => 'Payment Paid Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
    // Pending Orders 
    public function PendingOrders()
    {
        $orders = Order::with('user', 'qty', 'user')->where('status', 'pending')->where('userrole_id', 1)->orderBy('id', 'DESC')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Customer Pending Orders'), Auth::user()->id)) {
            return view('backend.orders.pending_orders', compact('orders'));
        } else {
            return view('401');
        }
    } // end mehtod 

    public function sellerPendingOrders()
    {
        $orders = Order::with('qty', 'user')->where('status', 'pending')->where('userrole_id', 2)->orderBy('id', 'DESC')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Reseller Pending Orders'), Auth::user()->id)) {
            return view('backend.orders.seller_pending_orders', compact('orders'));
        } else {
            return view('401');
        }
    }
    public function PendingApprove($order_id)
    {
        $this->approvePending($order_id, 0);

        $notification = array(
            'message' => 'Order moved to confirmed status successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
    public function sellerPendingApprove($order_id)
    {
        $this->approvePending($order_id, 1);

        $notification = array(
            'message' => 'Order moved to confirmed status successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    private function approvePending($order_id, $isSeller)
    {

        Order::where('id', $order_id)->update([
            'status' => 'confirmed',
            'confirmed_date' => Carbon::now()
        ]);

        if ($isSeller == 0) {
            $mobile = Order::where('id', $order_id)->pluck('phone')->first();
        } else {
            $userorder = Order::join('users', 'users.id', 'orders.user_id')->where('orders.id', $order_id)->select('users.phone', 'users.email', 'users.name')->first();
            $mobile = $userorder->phone;
        }

        Http::post('http://pay4sms.in/sendsms/?token=9a2edf41bc2760c4c5bb0b592eaf7bfb&credit=2&sender=NITHTX&message=Thanks for shopping with us! Your order is confirmed and will be shipped shortly. Check your status here: https://nithitex.com/track-your-order ,Nithitex.&number=' . $mobile . '');
    }

    // Confirmed Orders 
    public function ConfirmedOrders()
    {
        $orders = Order::with('qty', 'user')->where('status', 'confirmed')->where('userrole_id', 1)->orderBy('id', 'DESC')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Customer Confirmed Orders'), Auth::user()->id)) {
            return view('backend.orders.confirmed_orders', compact('orders'));
        } else {
            return view('401');
        }
    } // end mehtod 

    public function sellerConfirmedOrders()
    {
        $orders = Order::with('qty', 'user')->where('status', 'confirmed')->where('userrole_id', 2)->orderBy('id', 'DESC')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Reseller Confirmed Orders'), Auth::user()->id)) {
            return view('backend.orders.seller_confirmed_orders', compact('orders'));
        } else {
            return view('401');
        }
    }
    public function confirmedApprove($order_id)
    {
        $this->approveConfirm($order_id);
        $notification = array(
            'message' => 'Order moved to processing status successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // end mehtod 

    public function sellerConfirmedApprove($order_id)
    {
        $this->approveConfirm($order_id);
        $notification = array(
            'message' => 'Order moved to processing status successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    private function approveConfirm($order_id)
    {

        Order::where('id', $order_id)->update([
            'status' => 'processing',
            'processing_date' => Carbon::now(),
        ]);
    }

    // Processing Orders 
    public function ProcessingOrders()
    {
        $orders = Order::with('qty', 'user')->where('status', 'processing')->where('userrole_id', 1)->orderBy('id', 'DESC')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Customer Processing Orders'), Auth::user()->id)) {
            return view('backend.orders.processing_orders', compact('orders'));
        } else {
            return view('401');
        }
    } // end mehtod 

    public function sellerProcessingOrders()
    {
        $orders = Order::with('qty', 'user')->where('status', 'processing')->where('userrole_id', 2)->orderBy('id', 'DESC')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Reseller Processing Orders'), Auth::user()->id)) {
            return view('backend.orders.seller_processing_orders', compact('orders'));
        } else {
            return view('401');
        }
    }
    public function processingApprove($order_id)
    {
        $this->approveProcessing($order_id);

        $notification = array(
            'message' => 'Order moved to picked status successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } // end mehtod 

    public function sellerProcessingApprove($order_id)
    {
        $this->approveProcessing($order_id);

        $notification = array(
            'message' => 'Order moved to picked status successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    private function approveProcessing($order_id)
    {

        Order::where('id', $order_id)->update([
            'status' => 'picked',
            'picked_date' => Carbon::now(),
        ]);
    }

    // Picked Orders 
    public function PickedOrders()
    {
        $orders = Order::with('qty', 'user')->where('status', 'picked')->where('userrole_id', 1)->orderBy('id', 'DESC')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Customer Picked Orders'), Auth::user()->id)) {
            return view('backend.orders.picked_orders', compact('orders'));
        } else {
            return view('401');
        }
    } // end mehtod 
    public function sellerPickedOrders()
    {
        $orders = Order::with('qty', 'user')->where('status', 'picked')->where('userrole_id', 2)->orderBy('id', 'DESC')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Reseller Picked Orders'), Auth::user()->id)) {
            return view('backend.orders.seller_picked_orders', compact('orders'));
        } else {
            return view('401');
        }
    }
    public function pickedApprove(Request $request, $order_id)
    {

        $request->validate([
            'track_number' => 'required|unique:orders,track_number,' . $order_id,
        ]);

        $track_number =  $request->track_number;
        $track_url =  $request->track_url;

        $this->approvePicked($track_number, $track_url, $order_id, 0);

        $notification = array(
            'message' => 'Order moved to shipped status successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // end mehtod 

    public function sellerPickedApprove(Request $request, $order_id)
    {
        $request->validate([
            'track_number' => 'required|unique:orders,track_number,' . $order_id,
        ]);

        $track_number =  $request->track_number;
        $track_url =  $request->track_url;

        $this->approvePicked($track_number, $track_url, $order_id, 1);

        $notification = array(
            'message' => 'Order moved to shipped status successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }


    private function approvePicked($track_number, $track_url, $order_id, $isSeller)
    {

        Order::where('id', $order_id)->update([
            'status' => 'shipped',
            'shipped_date' => Carbon::now(),
            'track_number' => $track_number,
            'track_url' => $track_url,
        ]);

        $userorder = Order::join('users', 'users.id', 'orders.user_id')->where('orders.id', $order_id)->select('users.phone', 'users.email', 'users.name')->first();
        if ($isSeller == 0) {
            $mobile = Order::where('id', $order_id)->pluck('phone')->first();
            $username = Order::where('id', $order_id)->pluck('name')->first();
        } else {
            $mobile = $userorder->phone;
            $username = $userorder->name;
        }

        if ($track_number) {
            $url = "https://nithitex.com/track-your-order";
            Http::post('http://pay4sms.in/sendsms/?token=9a2edf41bc2760c4c5bb0b592eaf7bfb&credit=2&sender=NITHTX&message=Your Tracking ID is ' . $track_number . ' Tracking URK : ' . $url . ' Thanks, Regards - NITHTX&number=' . $mobile . '');
        }

        Http::post('http://pay4sms.in/sendsms/?token=9a2edf41bc2760c4c5bb0b592eaf7bfb&credit=2&sender=NITHTX&message=Hi ' . $username . ', your package has now been shipped. View tracking details : https://nithitex.com/track-your-order , Nithitex.&number=' . $mobile . '');
    }

    // Shipped Orders 
    public function ShippedOrders()
    {
        $orders = Order::with('qty', 'user')->where('status', 'shipped')->where('userrole_id', 1)->orderBy('id', 'DESC')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Customer Shipped Orders'), Auth::user()->id)) {
            return view('backend.orders.shipped_orders', compact('orders'));
        } else {
            return view('401');
        }
    } // end mehtod 

    public function sellerShippedOrders()
    {
        $orders = Order::with('qty', 'user')->where('status', 'shipped')->where('userrole_id', 2)->orderBy('id', 'DESC')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Reseller Shipped Orders'), Auth::user()->id)) {
            return view('backend.orders.seller_shipped_orders', compact('orders'));
        } else {
            return view('401');
        }
    }

    public function shippedApprove($order_id)
    {

        $this->approveShipped($order_id, 0);

        $notification = array(
            'message' => 'Order moved to delivered status successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // end mehtod 

    public function sellerShippedApprove($order_id)
    {

        $this->approveShipped($order_id, 1);
        $notification = array(
            'message' => 'Order moved to delivered status successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    private function approveShipped($order_id, $isSeller)
    {
        $userorder = Order::join('users', 'users.id', 'orders.user_id')->where('orders.id', $order_id)->select('orders.*', 'users.phone', 'users.email', 'users.name')->first();

        $orderCount = Order::where('status', 'delivered')->where('user_id', $userorder->user_id)->count();
        if ($userorder->delivered_date == null) {
            $userReferralHis = UserReferralHistory::where('user_id', $userorder->user_id)->first();
            if ($orderCount == 0) {
                $this->addReferrerPoints($userReferralHis->referred_by);
            }
        }

        Order::where('id', $order_id)->update([
            'status' => 'delivered',
            'delivered_date' => Carbon::now()
        ]);


        $payment_type = Order::where('id', $order_id)->pluck('payment_type')->first();

        if ($isSeller == 0) {
            $mobile = Order::where('id', $order_id)->pluck('phone')->first();
            $email = Order::where('id', $order_id)->pluck('email')->first();
            $username = Order::where('id', $order_id)->pluck('name')->first();
        } else {
            $mobile = $userorder->phone;
            $email = $userorder->email;
            $username = $userorder->name;
        }

        if ($payment_type == 'Cash On Delivery') {
            //  Start Send Email  

            Order::where('id', $order_id)->update([
                'invoice_no' => 'NITHTX' . mt_rand(10000000, 99999999)
            ]);

            $order = Order::with('user')->where('id', $order_id)->first();
            $orderItem = OrderItem::with('product')->where('order_id', $order_id)->orderBy('id', 'DESC')->get();

            $pdf = PDF::loadView(($isSeller == 0 ? 'frontend.user.order.order_invoice' : 'seller.dashboard.order_invoice'), compact('order', 'orderItem'));
            $content = $pdf->download()->getOriginalContent();

            Mail::to($email)->send(new OrderMail($pdf, $order->invoice_no, 'cod'));

            $make_name = 'invoice/' . $order->invoice_no . '.pdf';
            $save_url = $this->fileUploadS3Bucket($make_name, $content, 1);

            Order::where('id', $order->id)->update([
                'invoice' => $save_url
            ]);
        }

        Http::post('http://pay4sms.in/sendsms/?token=9a2edf41bc2760c4c5bb0b592eaf7bfb&credit=2&sender=NITHTX&message=Hi ' . $username . ', your order has now been delivered. View tracking details : https://nithitex.com/track-your-order , Nithitex.&number=' . $mobile . '');
    }

    // Delivered Orders 
    public function DeliveredOrders()
    {
        $orders = Order::with('qty', 'user')->where('status', 'delivered')->where('userrole_id', 1)->orderBy('id', 'DESC')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Customer Delivered Orders'), Auth::user()->id)) {
            return view('backend.orders.delivered_orders', compact('orders'));
        } else {
            return view('401');
        }
    } // end mehtod 

    public function sellerDeliveredOrders()
    {
        $orders = Order::with('qty', 'user')->where('status', 'delivered')->where('userrole_id', 2)->orderBy('id', 'DESC')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Reseller Delivered Orders'), Auth::user()->id)) {
            return view('backend.orders.seller_delivered_orders', compact('orders'));
        } else {
            return view('401');
        }
    }

    // Cancel Orders 
    public function CancelOrders()
    {
        $orders = Order::where('status', 'cancelled')->where('userrole_id', 1)->orderBy('id', 'DESC')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Customer Cancelled Orders'), Auth::user()->id)) {
            return view('backend.orders.cancel_orders', compact('orders'));
        } else {
            return view('401');
        }
    } // end mehtod 

    public function sellerCancelOrders()
    {
        $orders = Order::where('status', 'cancelled')->where('userrole_id', 2)->orderBy('id', 'DESC')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Reseller Cancelled Orders'), Auth::user()->id)) {
            return view('backend.orders.seller_cancel_orders', compact('orders'));
        } else {
            return view('401');
        }
    }

    /// Product View With Ajax
    public function OrderprintAjax($id)
    {
        $order = Order::with('user', 'seller')->findOrFail($id);
        $orderitems = OrderItem::where('order_id', $order->id)->get();
        return view('backend.orders.print', compact('order'));

        // ));

    } // end method 
    public function prnpriview($id)
    {
        $order = Order::with('user')->findOrFail($id);
        $orderitems = OrderItem::where('order_id', $order->id)->get();
        return view('backend.orders.printprivew', compact('order'));
    } // end method

    public function getDeliveredOrders()
    {
        $allDeliveredOrder = Order::select('orders.*', 'users.name')
            ->join('users', 'orders.user_id', 'users.id')
            ->orderBy('orders.id', 'DESC')
            ->where('orders.status', 'delivered')
            ->where('orders.userrole_id', [1])
            ->get();

        return datatables()->of($allDeliveredOrder)->toJson();
    } // end method

    public function getSellerDeliverdOrders()
    {
        $allsellerDeliveredOrder = Order::select('orders.*', 'users.name as userName', 'users.phone as userphone', 'users.city_name as usercity', 'users.state_name as userstate')
            ->join('users', 'orders.user_id', 'users.id')
            ->orderBy('orders.id', 'DESC')
            ->where('orders.status', 'delivered')
            ->where('orders.userrole_id', [2])
            ->get();

        return datatables()->of($allsellerDeliveredOrder)->toJson();
    } // end method

    public function order_export(Request $request)
    {
        try {
            $order_query = Order::select(
                'order_date as Order Date',
                'order_number as Order Number',
                'users.name as Reseller Name',
                'orders.name as Order To',
                'tot_Qty as Qty	',
                'sub_total as Sub ',
                'coupon_discount as Discount',
                'shipping_charge as Shipping',
                'margin_amount as Margin',
                'amount as Net Amount',
                'status as Delivery Status',
                'payment_status as Payment Status',
                'payment_type as Payment Method',
            )
                ->leftJoin('users', 'orders.user_id', 'users.id')
                ->where('orders.userrole_id', 2);


            if ($request->date) {
                $order_query = $order_query->where('orders.created_at', $request->date);
            }

            $all_orders = $order_query->get();

            return (new FastExcel($all_orders))->download('Resellerorders.xlsx');
        } catch (Exception $e) {
            dd($e);
            $notification = array(
                'message' => 'Something went wrong!',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }
}
