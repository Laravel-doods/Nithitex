      <header class="header-area transparent-bar section-padding-1">
          <div class="container-full fixed-top" style="background-color:#fff;">
              <div class="header-large-device">
                  <div class="header-top header-top-ptb-1 border-bottom-1">
                      <div class="row">
                          <div class="col-xl-9 col-lg-9">
                              <div class="header-offer-wrap">
                                  {{-- <i class="icon-screen-smartphone"></i><a href="tel:+917092957279" >+91 7092957279</a></li> --}}
                                  @php
                                      $marquee = App\Models\ShopInformation::find(1);
                                  @endphp
                                  <marquee behavior="" direction="" class="text-danger">{{ $marquee->announcement }}
                                  </marquee>
                              </div>
                          </div>
                          <div class="col-xl-3 col-lg-3">
                              <div class="header-top-right">
                                  <div class="same-style-wrap">
                                      <div class="same-style same-style-border track-order">
                                          <a href="{{ route('seller.track_order') }}">Track Order</a>
                                      </div>
                                  </div>
                                  <div class="social-style-1 social-style-1-mrg">
                                      <a href="{{ $marquee->facebook }}" target="_blank">
                                          <i class="icon-social-twitter"></i>
                                      </a>
                                      <a href="{{ $marquee->twitter }}" target="_blank">
                                          <i class="icon-social-facebook"></i>
                                      </a>
                                      <a href="{{ $marquee->instagram }}" target="_blank">
                                          <i class="icon-social-instagram"></i>
                                      </a>
                                      <a href="{{ $marquee->youtube }}" target="_blank">
                                          <i class="icon-social-youtube"></i>
                                      </a>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="header-bottom">
                      <div class="row align-items-center">
                          <div class="col-xl-2 col-lg-2">
                              <div class="logo">
                                  <a href="{{ route('seller.home') }}">
                                      <img src="{{ asset('frontend/assets/images/logo/logo.png') }}" alt="logo">
                                  </a>
                              </div>
                          </div>
                          <div class="col-xl-8 col-lg-7">
                              <div class="main-menu main-menu-padding-1 main-menu-lh-1 text-center">
                                  <nav>
                                      <ul>
                                          <li>
                                              <a href="{{ route('seller.home') }}">Home</a>
                                          </li>
                                          <li>
                                              <a href="{{ route('seller.product.shop') }}">Shop</a>
                                          </li>
                                          @php
                                              $main_categories_list = App\Models\MainCategory::orderBy(
                                                  'main_category_name',
                                                  'ASC',
                                              )->get();
                                              $categories = App\Models\Category::orderby('category_name', 'ASC')->get();
                                          @endphp
                                          <li class="main-category">
                                              <a>Categories</a>
                                              <ul class="sub-menu-style">
                                                  @foreach ($main_categories_list as $main)
                                                      <li class="main-category-item">
                                                          <div>
                                                              @if ($main->categories->count() > 0)
                                                                  <span class="toggle-arrow px-1"><i
                                                                          class="fas fa-angle-right"
                                                                          style="color:#535050"></i></span>
                                                              @endif
                                                              {{ $main->main_category_name }}
                                                          </div>
                                                          <ul class="sub-category-list pt-2 px-4">
                                                              @foreach ($main->categories as $item)
                                                                  <li>
                                                                      <a href="{{ url('category/product/' . $item->id) }}"
                                                                          style="font-size: 13px;">{{ $item->category_name }}</a>
                                                                  </li>
                                                              @endforeach
                                                          </ul>
                                                      </li>
                                                  @endforeach
                                              </ul>
                                          </li>
                                          <li>
                                              <a href="{{ route('seller.product.offers') }}">Nithitex's Offers</a>
                                          </li>
                                          <li>
                                              <a href="{{ route('today.offer') }}">Today Offer</a>
                                          </li>
                                          <li>
                                              <a href="{{ route('seller.about') }}">About us</a>
                                          </li>
                                          <li>
                                              <a href="{{ route('seller.contact') }}">Contact us</a>
                                          </li>
                                          <li>
                                              <img src="{{ asset('frontend/assets/images/reseller.png') }}">
                                          </li>
                                      </ul>
                                  </nav>
                              </div>
                          </div>
                          <div class="col-xl-2 col-lg-3">
                              <div class="header-action header-action-flex header-action-mrg-right">
                                  <div class="same-style-2 header-search-1">
                                      <a class="search-toggle">
                                          <i class="icon-magnifier s-open"></i>
                                          <i class="icon_close s-close"></i>
                                      </a>
                                      <div class="search-wrap-1">
                                          <form action="{{ route('seller.product.search') }}" method="GET">
                                              @csrf
                                              <input placeholder="Search products…" type="text" id="search"
                                                  name="search" autocomplete="off">
                                              <button type="submit" class="button-search">
                                                  <i class="icon-magnifier"></i>
                                              </button>
                                          </form>
                                      </div>
                                  </div>
                                  <div class="same-style-2">
                                      <a href="{{ route('seller.wishlist') }}">
                                          <i class="icon-heart"></i>
                                          <span class="pro-count red" id="wishlist_count" value=""></span>
                                      </a>
                                  </div>
                                  <div class="same-style-2 header-cart">
                                      @auth
                                          <a class="cart-active">
                                              <i class="icon-basket-loaded"></i>
                                              <span class="pro-count red" id="cartQty" value=""></span>
                                          </a>
                                      @else
                                          <a href="{{ route('user.login') }}">
                                              <i class="icon-basket-loaded"></i>
                                              <span class="pro-count red" id="cartQty" value=""></span>
                                          </a>
                                      @endauth
                                  </div>
                                  <div class="same-style-2">
                                      @auth
                                          @php
                                              $user = App\Models\User::where('id', Auth::user()->id)->first();
                                          @endphp
                                          <a href="{{ route('seller.dashboard') }}">
                                              <img style="border-radius: 50%;height: 42px;width: 42px;"
                                                  src="@if ($user->profile_photo_path == null) {{ asset('profile.png') }} 
                        @else {{ asset('upload/user_images/' . $user->profile_photo_path) }} @endif">
                                          </a>
                                      @else
                                          <a href="{{ route('user.login') }}">
                                              <i class="icon-user"></i>
                                          </a>
                                      @endauth
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="header-small-device small-device-ptb-1">
                  <div class="row align-items-center">
                      <div class="col-5">
                          <div class="mobile-logo">
                              <a href="{{ route('seller.home') }}">
                                  <img alt="" src="{{ asset('frontend/assets/images/logo/logo.png') }}">
                              </a>
                          </div>
                      </div>
                      <div class="col-7">
                          <div class="header-action header-action-flex">

                              <div class="same-style-2">
                                  <a href="{{ route('seller.wishlist') }}">
                                      <i class="icon-heart"></i>
                                      <span class="pro-count red" id="wishlist_count" value=""></span>
                                  </a>
                              </div>
                              <div class="same-style-2 header-cart">
                                  @auth
                                      <a class="cart-active">
                                          <i class="icon-basket-loaded"></i>
                                          <span class="pro-count red" id="cartQty" value=""></span>
                                      </a>
                                  @else
                                      <a href="{{ route('user.login') }}">
                                          <i class="icon-basket-loaded"></i>
                                          <span class="pro-count red" id="cartQty" value=""></span>
                                      </a>
                                  @endauth
                              </div>
                              <div class="same-style-2">
                                  @auth
                                      @php
                                          $user = App\Models\User::where('id', Auth::user()->id)->first();
                                      @endphp
                                      <a href="{{ route('seller.dashboard') }}">
                                          <img style="border-radius: 50%;height: 42px;width: 42px;"
                                              src="@if ($user->profile_photo_path == null) {{ asset('profile.png') }} 
                        @else {{ asset('upload/user_images/' . $user->profile_photo_path) }} @endif">
                                      </a>
                                  @else
                                      <a href="{{ route('user.login') }}">
                                          <i class="icon-user"></i>
                                      </a>
                                  @endauth
                              </div>
                              <div class="same-style-2 main-menu-icon">
                                  <a class="mobile-header-button-active">
                                      <i class="icon-menu"></i>
                                  </a>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </header>
      <!-- mini cart start -->
      <div class="sidebar-cart-active">
          <div class="sidebar-cart-all">
              <a class="cart-close">
                  <i class="icon_close"></i>
              </a>
              <div class="cart-content">
                  <h3>Shopping Cart</h3>
                  <ul>
                      <div id="miniCart">

                      </div>


                  </ul>
                  <div class="cart-total">
                      <h4>Subtotal: <span id="cartSubTotal"></span>
                      </h4>
                  </div>
                  <div class="cart-checkout-btn">
                      <a class="btn-hover cart-btn-style" href="{{ route('seller.mycart') }}">view cart</a>
                      <a class="no-mrg btn-hover cart-btn-style" href="{{ route('seller.checkout') }}">checkout</a>
                  </div>
              </div>
              <div class="empty-cart hidden">
                  <img src="{{ asset('/frontend/assets/images/cart/empty_cart.png') }}">
              </div>
          </div>
      </div>

      <div class="col-xl-12 col-lg-12 d-flex align-items-center justify-content-center"
          style="background-color: #d4bb33; padding: 4px;">
          <div class="need-menu main-menu main-menu-padding-1">
              <nav>
                  <ul>
                      <li>
                          <a href="{{ route('seller.home') }}">Home</a>
                      </li>
                      <li>
                          <a href="{{ route('seller.product.shop') }}">Shop</a>
                      </li>
                      @php
                          $categories = App\Models\Category::orderby('category_name', 'ASC')->get();
                      @endphp
                      <li class="main-category">
                          <a>Categories</a>
                          <ul class="sub-menu-style">
                              @foreach ($main_categories_list as $main)
                                  <li class="main-category-items">
                                      <div>
                                          @if ($main->categories->count() > 0)
                                              <span class="toggle-arrow px-1"><i class="fas fa-angle-right"
                                                      style="color:#535050"></i></span>
                                          @endif
                                          {{ $main->main_category_name }}
                                      </div>
                                      <ul class="sub-category-lists pt-2 px-3">
                                          @foreach ($main->categories as $item)
                                              <li>
                                                  <a
                                                      href="{{ url('category/product/' . $item->id) }}">{{ $item->category_name }}</a>
                                              </li>
                                          @endforeach
                                      </ul>
                                  </li>
                              @endforeach
                          </ul>
                      </li>
                      <li>
                          <a href="{{ route('seller.product.offers') }}">Offers</a>
                      </li>
                      <li>
                          <a href="{{ route('today.offer') }}">Today Offer</a>
                      </li>
                  </ul>
              </nav>
          </div>
      </div>
      <div class="mt-10">
          @php
              $marquee = App\Models\ShopInformation::find(1);
          @endphp
          <marquee behavior="" direction="" class="text-danger">{{ $marquee->announcement }}</marquee>
      </div>
      <!-- Mobile menu start -->
      <div class="mobile-header-active mobile-header-wrapper-style">
          <div class="clickalbe-sidebar-wrap">
              <a class="sidebar-close">
                  <i class="icon_close"></i>
              </a>
              <div class="mobile-header-content-area">
                  <div class="mobile-search mobile-header-padding-border-1">
                      <form action="{{ route('product.search') }}" method="GET">
                          @csrf
                          <input placeholder="Search products…" type="text" id="search" name="search"
                              autocomplete="off">
                          <button type="submit" class="button-search">
                              <i class="icon-magnifier"></i>
                          </button>
                      </form>
                  </div>
                  <div class="mobile-menu-wrap mobile-header-padding-border-2">
                      <!-- mobile menu start -->
                      <nav>
                          <ul class="mobile-menu">
                              <li class="menu-item-has-children">
                                  <a href="{{ route('seller.home') }}">Home</a>
                              </li>
                              <li class="menu-item-has-children ">
                                  <a href="{{ route('seller.product.shop') }}">shop</a>
                              </li>
                              <li class="main-category">
                                  <a>Categories</a>
                                  <ul class="sub-menu-style">
                                      @foreach ($main_categories_list as $main)
                                          <li class="main-category-items">
                                              <div>
                                                  @if ($main->categories->count() > 0)
                                                      <span class="toggle-arrow px-1"><i class="fas fa-angle-right"
                                                              style="color:#535050"></i></span>
                                                  @endif
                                                  {{ $main->main_category_name }}
                                              </div>
                                              <ul class="sub-category-lists pt-1 px-3">
                                                  @foreach ($main->categories as $item)
                                                      <li>
                                                          <a
                                                              href="{{ url('category/product/' . $item->id) }}">{{ $item->category_name }}</a>
                                                      </li>
                                                  @endforeach
                                              </ul>
                                          </li>
                                      @endforeach
                                  </ul>
                              </li>
                              <li>
                                  <a href="{{ route('seller.about') }}">About us</a>
                              </li>
                              <li>
                                  <a href="{{ route('seller.contact') }}">Contact us</a>
                              </li>
                          </ul>
                      </nav>
                      <!-- mobile menu end -->
                  </div>
                  <div class="mobile-header-info-wrap mobile-header-padding-border-3">
                      <div class="single-mobile-header-info">
                          <a href="{{ route('track_order') }}">
                              <i class="lastudioicon-pin-3-2"></i> Track Order </a>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <script>
          $(document).ready(function() {
              $('.main-category-item').hover(function() {
                  $(this).children('.sub-category-list').stop(true, true).slideDown(200);
              }, function() {
                  $(this).children('.sub-category-list').stop(true, true).slideUp(200);
              });

              $('.main-category-items').click(function() {
                  $(this).find('.sub-category-lists').slideToggle(200);
                  $(this).find('.toggle-arrow i').toggleClass('fa-angle-right fa-angle-up');
              });
          });
      </script>
