<nav class="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            Nithi Tex
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item nav-category">Main</li>
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                    <i class="link-icon" data-feather="box"></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item nav-category">Products</li>

            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#emails" role="button" aria-expanded="false"
                    aria-controls="emails">
                    <i class="link-icon" data-feather="layers"></i>
                    <span class="link-title">Products</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="emails">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('main-category.all') }}" class="nav-link">Main Categories</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('category.all') }}" class="nav-link">Categories</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('color.all') }}" class="nav-link">Colors</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('product.all') }}" class="nav-link">Add New Products</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('product.list') }}" class="nav-link">All Products</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('product.stock') }}" class="nav-link">Product Stock maintenance</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item nav-category">Customer</li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#customers" role="button" aria-expanded="false"
                    aria-controls="forms">
                    <i class="link-icon" data-feather="users"></i>
                    <span class="link-title">Customers</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="customers">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('customer.all') }}" class="nav-link">All Customers</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#uiComponents" role="button" aria-expanded="false"
                    aria-controls="uiComponents">
                    <i class="link-icon" data-feather="feather"></i>
                    <span class="link-title">Customer Orders</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="uiComponents">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('order.all') }}" class="nav-link">Customer All Orders</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('orders-pending') }}" class="nav-link">Pending Orders</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('orders-confirmed') }}" class="nav-link">Confirmed Orders</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('orders-processing') }}" class="nav-link">Processing Orders</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('orders-picked') }}" class="nav-link">Picked Orders</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('orders-shipped') }}" class="nav-link">Shipped Orders</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('orders-delivered') }}" class="nav-link">Delivered Orders</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('orders-cancel') }}" class="nav-link">Cancelled Orders</a>
                        </li>
                    </ul>
                </div>
            </li>


            <li class="nav-item nav-category">Reseller</li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#resellers" role="button" aria-expanded="false"
                    aria-controls="forms">
                    <i class="link-icon" data-feather="users"></i>
                    <span class="link-title">Resellers</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="resellers">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('resellers.all') }}" class="nav-link">All Resellers</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('resellers.request') }}" class="nav-link">Reseller Requests</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#reseller" role="button" aria-expanded="false"
                    aria-controls="reseller">
                    <i class="link-icon" data-feather="feather"></i>
                    <span class="link-title">Reseller Orders</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="reseller">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('seller.order.all') }}" class="nav-link">Reseller All Orders</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('seller.orders-pending') }}" class="nav-link">Pending Orders</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('seller.orders-confirmed') }}" class="nav-link">Confirmed Orders</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('seller.orders-processing') }}" class="nav-link">Processing Orders</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('seller.orders-picked') }}" class="nav-link">Picked Orders</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('seller.orders-shipped') }}" class="nav-link">Shipped Orders</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('seller.orders-delivered') }}" class="nav-link">Delivered Orders</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('seller.orders-cancel') }}" class="nav-link">Cancelled Orders</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item nav-category">Staff</li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#staff" role="button" aria-expanded="false"
                    aria-controls="staff">
                    <i class="link-icon" data-feather="feather"></i>
                    <span class="link-title">Staff Orders</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="staff">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('staff.order.all') }}" class="nav-link">Staff All Orders</a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <li class="nav-item nav-category">Return/Cancel</li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#returnrequest" role="button"
                    aria-expanded="false" aria-controls="returnrequest">
                    <i class="link-icon" data-feather="database"></i>
                    <span class="link-title">Return Request</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="returnrequest">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('all.request') }}" class="nav-link">Return Orders List</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('return.request') }}" class="nav-link">Customer Return Request</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('seller.return.request') }}" class="nav-link">Resellers Return
                                Request</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#cancelrequest" role="button"
                    aria-expanded="false" aria-controls="cancelrequest">
                    <i class="link-icon" data-feather="database"></i>
                    <span class="link-title">Cancel Request</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="cancelrequest">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('all.cancel.request') }}" class="nav-link">Cancel Orders List</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cancel.request') }}" class="nav-link">Customer Cancel Request</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('seller.cancel.request') }}" class="nav-link">Resellers Cancel
                                Request</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item nav-category">Reports</li>
            <li class="nav-item">
                <a href="{{ route('report.out_of_stock') }}" class="nav-link">
                    <i class="link-icon" data-feather="check-circle"></i>
                    <span class="link-title">Out Of Stock</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('report.stock') }}" class="nav-link">
                    <i class="link-icon" data-feather="check-square"></i>
                    <span class="link-title">Stock</span></a>
            </li>
            <li class="nav-item">
                <a href="{{ route('view.analytics') }}" class="nav-link">
                    <i class="link-icon" data-feather="bar-chart"></i>
                    <span class="link-title">View Analytics</span></a>
            </li>

            <li class="nav-item nav-category">Shop Settings</li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#charts" role="button" aria-expanded="false"
                    aria-controls="charts">
                    <i class="link-icon" data-feather="pie-chart"></i>
                    <span class="link-title">Settings</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="charts">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('about.all') }}" class="nav-link">Company About</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('policy.all') }}" class="nav-link">Company Policies</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('slider.all') }}" class="nav-link">Home Slider Setup</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('information.all') }}" class="nav-link">Shop Information</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('state.all') }}" class="nav-link">State Master</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('coupon.all') }}" class="nav-link">Generate Coupon</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('loyalty.management') }}" class="nav-link">Loyalty Management</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a href="{{ route('notification.view') }}" class="nav-link">
                    <i class="link-icon" data-feather="bell"></i>
                    <span class="link-title">Notification</span></a>
            </li>

            <li class="nav-item nav-category">Manage Referral</li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#referral" role="button" aria-expanded="false"
                    aria-controls="referral">
                    <i class="link-icon" data-feather="users"></i>
                    <span class="link-title">Referrals</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="referral">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('referralsettings') }}" class="nav-link">Referral Settings</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('referral-history') }}" class="nav-link">Referral History</a>
                        </li>
                        {{-- <li class="nav-item">
                            <a href="{{ route('referralpaymenthistory') }}" class="nav-link">Payment History</a>
                        </li> --}}
                    </ul>
                </div>
            </li>

            <li class="nav-item nav-category">Manage Staffs</li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#tables" role="button" aria-expanded="false"
                    aria-controls="tables">
                    <i class="link-icon" data-feather="users"></i>
                    <span class="link-title">Staffs</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="tables">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ url('role') }}" class="nav-link">Roles & Permissions</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('assign_role_users') }}" class="nav-link">Assign Roles to Staffs</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('user') }}" class="nav-link">All Staffs</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>
