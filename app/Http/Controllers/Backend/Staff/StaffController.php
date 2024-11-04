<?php

namespace App\Http\Controllers\Backend\Staff;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Product;
use App\Models\ProductVariants;
use App\Models\StaffOrder;
use App\Models\StaffOrderItems;
use App\Traits\Utils;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    use Utils;

    public function staffView()
    {
        $data = Admin::where('id', '!=', 1)->get();

        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Staff All Orders'), Auth::user()->id)) {
            return view('backend.staff.all_staffs.all_staffs', compact('data'));
        } else {
            return view('401');
        }
    }
    public function staffOrderView()
    {
        $stafforders = StaffOrder::orderBy('id', 'DESC')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Staff All Orders'), Auth::user()->id)) {
            return view('backend.staff.orders.stafforder', compact('stafforders'));
        } else {
            return view('401');
        }
    }

    public function getAllStaffOrder()
    {
        $allStaffOrder = StaffOrder::orderBy('id', 'DESC')
        ->get();

        return datatables()->of($allStaffOrder)
        ->addColumn('action', function ($row) {
            $html = '<a href="' . route('stafforder.details', $row->id) . '" class="btn btn-info" title="View Details"><i class="fa fa-eye"></i> Update</a>';
            return $html;
        })
            ->toJson();
    }
    

    public function paymentPaid($order_id)
    {

        StaffOrder::where('id', $order_id)->update([
            'payment_status' => "paid"
        ]);

        $notification = array(
            'message' => 'Payment Paid Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function paymentUnpaid($order_id)
    {

        StaffOrder::where('id', $order_id)->update([
            'payment_status' => "Unpaid"
        ]);

        $notification = array(
            'message' => 'Payment Unpaid Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function staffOrdersDetails($order_id)
    {
        $stafforder = StaffOrder::where('id', $order_id)->first();
        $staffOrderItem = StaffOrderItems::with('product')->where('staff_order_id', $order_id)->orderBy('id', 'DESC')->get();
        if ($this->checkUserPermission(Auth::user()->hasPermissionTo('Staff All Orders'), Auth::user()->id)) {
            return view('backend.staff.orders.staff_orderdetail', compact('stafforder', 'staffOrderItem'));
        } else {
            return view('401');
        }
    }

    public function staffOrderStatusUpdate(Request $request)
    {
        $this->updateOrderStatus($request);

        $notification = array(
            'message' => 'Order Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    private function updateOrderStatus(Request $request)
    {
        $id = $request->order_id;

        if ($request->order_status == 'confirmed') {

            StaffOrder::findOrFail($id)->update([
                'confirmed_date' => Carbon::now(),
                'status' => $request->order_status,
            ]);
        } elseif ($request->order_status == 'processing') {

            StaffOrder::findOrFail($id)->update([
                'processing_date' => Carbon::now(),
                'status' => $request->order_status,
            ]);
        } elseif ($request->order_status == 'picked') {

            StaffOrder::findOrFail($id)->update([
                'picked_date' => Carbon::now(),
                'status' => $request->order_status,
            ]);
        } elseif ($request->order_status == 'shipped') {

            StaffOrder::findOrFail($id)->update([
                'shipped_date' => Carbon::now(),
                'status' => $request->order_status,
            ]);
        } elseif ($request->order_status == 'delivered') {

            StaffOrder::findOrFail($id)->update([
                'delivered_date' => Carbon::now(),
                'status' => $request->order_status,
            ]);
        } elseif ($request->order_status == 'cancelled') {

            StaffOrder::findOrFail($id)->update([
                'cancel_date' => Carbon::now(),
                'status' => $request->order_status,
            ]);
            $staffOrderItem = StaffOrderItems::where('staff_order_id', $id)->get();

            foreach ($staffOrderItem as $item) {
                $update_stock = Product::where('id', $item->product_id)->first();
                $update_stock->current_stock = $update_stock->current_stock + $item->qty;
                $update_stock->update();
                if($item->variant_size != null){
                    $variant = ProductVariants::where('product_id', $item->product_id)->where('size', $item->variant_size)->first();
                    $variant->stock = $variant->stock + $item->qty; 
                    $variant->update();
                }
            }
        }
    }
}
