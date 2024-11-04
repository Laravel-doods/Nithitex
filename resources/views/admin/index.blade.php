@extends('admin.admin_master')
@section('admin')
@section('title')
    Admin | Nithitex India's No 1 Online Saree Shop
@endsection
@php
    $orders = App\Models\Order::with('user')->where('status', 'pending')->orderBy('id', 'DESC')->limit(10)->get();
@endphp

<!-- Content Header (Page header) -->

<div class="page-content">

    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h3 class="mb-3 mb-md-0">Dashboard</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow">
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Live Customers</h6>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-12 col-xl-12">
                                    <h3 class="mb-1 mt-2 live-user-count">0</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- row -->

    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Customers</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow">
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">All Customers</h6>
                            </div>
                            @php
                                $user = App\Models\User::where('userrole_id', 1)->get()->count();
                            @endphp
                            <div class="row">
                                <div class="col-12 col-md-12 col-xl-12">
                                    <h3 class="mb-2">{{ $user }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">All Orders</h6>
                            </div>
                            @php
                                $allorders = App\Models\Order::where('userrole_id', 1)->get()->count();
                            @endphp
                            <div class="row">
                                <div class="col-12 col-md-12 col-xl-12">
                                    <h3 class="mb-2">{{ $allorders }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Pending Orders</h6>
                            </div>
                            @php
                                $pendingorders = App\Models\Order::where('status', 'pending')
                                    ->where('userrole_id', 1)
                                    ->get()
                                    ->count();
                            @endphp
                            <div class="row">
                                <div class="col-12 col-md-12 col-xl-12">
                                    <h3 class="mb-2">{{ $pendingorders }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Return Requests</h6>
                            </div>
                            @php
                                $returnorders = App\Models\Order::where('return_order', 1)
                                    ->where('userrole_id', 1)
                                    ->get()
                                    ->count();
                            @endphp
                            <div class="row">
                                <div class="col-12 col-md-12 col-xl-12">
                                    <h3 class="mb-2">{{ $returnorders }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- row -->

    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Resellers</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow">
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">All Resellers</h6>
                            </div>
                            @php
                                $seller = App\Models\Seller::where('is_verified', 1)->get()->count();
                            @endphp
                            <div class="row">
                                <div class="col-12 col-md-12 col-xl-12">
                                    <h3 class="mb-2">{{ $seller }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Pending Requests</h6>
                            </div>
                            @php
                                $pendingrequest = App\Models\seller::where('is_verified', 0)->get()->count();
                            @endphp
                            <div class="row">
                                <div class="col-12 col-md-12 col-xl-12">
                                    <h3 class="mb-2">{{ $pendingrequest }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">All Orders</h6>
                            </div>
                            @php
                                $sellerallorders = App\Models\Order::where('userrole_id', 2)->get()->count();
                            @endphp
                            <div class="row">
                                <div class="col-12 col-md-12 col-xl-12">
                                    <h3 class="mb-2">{{ $sellerallorders }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Pending Orders</h6>
                            </div>
                            @php
                                $sellerpendingorders = App\Models\Order::where('status', 'pending')
                                    ->where('userrole_id', 2)
                                    ->get()
                                    ->count();
                            @endphp
                            <div class="row">
                                <div class="col-12 col-md-12 col-xl-12">
                                    <h3 class="mb-2">{{ $sellerpendingorders }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- row -->
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Staffs</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow">
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">All Orders</h6>
                            </div>
                            @php
                                $staffOrder = App\Models\StaffOrder::get()->count();
                            @endphp
                            <div class="row">
                                <div class="col-12 col-md-12 col-xl-12">
                                    <h3 class="mb-2">{{ $staffOrder }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Pending Orders</h6>
                            </div>
                            @php
                                $staffpendingorders = App\Models\StaffOrder::where('status', 'pending')->get()->count();
                            @endphp
                            <div class="row">
                                <div class="col-12 col-md-12 col-xl-12">
                                    <h3 class="mb-2">{{ $staffpendingorders }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- row -->
    @hasrole('Super Admin')
        <div class="row">
            <div class="col-lg-12 col-xl-12 stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Orders</h3>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Order Date</th>
                                            <th>Order No</th>
                                            <th>Order By</th>
                                            <th>Qty</th>
                                            <th>Sub Total</th>
                                            <th>Discount</th>
                                            <th>Shipping</th>
                                            <th>Margin</th>
                                            <th>Net Amount</th>
                                            <th>Delivery Status</th>
                                            <th>Payment Status</th>
                                            <th>Payment Method</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyList">
                                        @php
                                            $serialNo = 1;
                                        @endphp
                                        @foreach ($orders as $item)
                                            <tr style="height:25px;">
                                                <td>{{ $serialNo }}</td>
                                                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                                <td>{{ $item->order_number }}</td>
                                                <td>{{ $item->user->name }}
                                                    <div class="text-muted bg-light p-1">
                                                        <small>{{ $item->userrole->user_role }}</small>
                                                    </div>
                                                </td>
                                                <td>{{ $item->tot_Qty }}</td>
                                                <td>{{ round($item->sub_total) }}</td>
                                                <td>{{ round($item->coupon_discount) }}</td>
                                                <td>{{ round($item->shipping_charge) }}</td>
                                                <td>
                                                    @if ($item->margin_amount)
                                                        {{ round($item->margin_amount) }}
                                                    @else
                                                        0
                                                    @endif
                                                </td>
                                                <td>{{ $item->amount }}</td>
                                                <td>
                                                    @if ($item->status == 'pending')
                                                        <span class="badge badge-pill badge-warning text-white"
                                                            style="background: #800080;"> Pending </span>
                                                    @elseif($item->status == 'confirmed')
                                                        <span class="badge badge-pill badge-warning text-white"
                                                            style="background: #0000FF;"> Confirm </span>
                                                    @elseif($item->status == 'processing')
                                                        <span class="badge badge-pill badge-warning text-white"
                                                            style="background: #FFA500;"> Processing </span>
                                                    @elseif($item->status == 'picked')
                                                        <span class="badge badge-pill badge-warning text-white"
                                                            style="background: #808000;"> Picked </span>
                                                    @elseif($item->status == 'shipped')
                                                        <span class="badge badge-pill badge-warning text-white"
                                                            style="background: #808080;"> Shipped </span>
                                                    @elseif($item->status == 'delivered')
                                                        <span class="badge badge-pill badge-warning text-white"
                                                            style="background: #008000;"> Delivered </span>
                                                    @elseif($item->status == 'cancelled')
                                                        <span class="badge badge-pill badge-warning text-white"
                                                            style="background: #80000b;"> Cancelled </span>
                                                    @elseif($item->status == 'returned')
                                                        <span class="badge badge-pill badge-warning text-white"
                                                            style="background: #80000b;"> Returned </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <label for="">
                                                        @if ($item->payment_status == 'paid')
                                                            <span
                                                                class="badge badge-pill badge-success text-white">{{ $item->payment_status }}
                                                            </span>
                                                        @else
                                                            <span
                                                                class="badge badge-pill badge-danger text-white">{{ $item->payment_status }}
                                                            </span>
                                                        @endif
                                                    </label>
                                                </td>
                                                <td>{{ $item->payment_type }}</td>
                                                <td>
                                                    @if ($item->userrole_id == 1)
                                                        <a href="{{ route('order.details', $item->id) }}"
                                                            class="btn btn-info" title="update">Update</a>
                                                    @else
                                                        <a href="{{ route('seller.order.details', $item->id) }}"
                                                            class="btn btn-info" title="update">Update</a>
                                                    @endif

                                                </td>
                                            </tr>
                                            @php
                                                $serialNo++;
                                            @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endhasrole
</div>
@endsection

<script src="{{ asset('backend/assets/js/common/jquery.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/common/common.js') }}"></script>
<script>
    $(document).ready(function() {
        function fetchLiveUserCount() {
            $.ajax({
                url: "{{ url('/live-users-count') }}", 
                method: 'GET',
                success: function(response) {
                    $('.live-user-count').text(response.count);
                    $('.live-user-count').css('color', 'green');
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching live user count:', error);
                }
            });
        }

        fetchLiveUserCount();

        setInterval(fetchLiveUserCount, 10000);
    });
</script>
