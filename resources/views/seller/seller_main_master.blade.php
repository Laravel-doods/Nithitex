<!DOCTYPE html>
<html class="no-js" lang="en">

<head>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Nithitex">
    <meta name="robots" content="all">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>@yield('title') </title>

    <meta name="description" content="Nithitex, Elampillai" />
    <meta name="keywords"
        content="Elampillai sarees, Nithi, sarees, Wedding saree, wedding, online saree, soft silk saree">


    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="Nithitex">
    <meta itemprop="description" content="Nithitex, Elampillai">
    <meta itemprop="image" content="https://www.nithitex.com/frontend/assets/images/logo/logo-banner.png">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@NithiTex">
    <meta name="twitter:title" content="India's No 1 Online Saree Shop - Nithitex">
    <meta name="twitter:description"
        content="Elampillai sarees, Nithi, sarees, Wedding saree, wedding, online saree, soft silk saree">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="https://www.nithitex.com/frontend/assets/images/logo/logo-banner.png">

    <!-- Open Graph data -->
    <meta property="og:title" content="India's No 1 Online Saree Shop - Nithitex" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://www.nithitex.com" />
    <meta property="og:image" content="https://www.nithitex.com/frontend/assets/images/logo/logo-banner.png" />
    <meta property="og:description" content="Elampillai sarees, Nithi, sarees, Wedding saree, wedding, online saree, soft silk saree" />
    <meta property="og:site_name" content="Nithitex" />
    <meta property="fb:app_id" content="">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('frontend/assets/images/favicon.png') }}">
    <!-- All CSS is here
    ============================================ -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendor/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendor/signericafat.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendor/cerebrisans.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendor/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendor/elegant.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendor/linear-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/plugins/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/plugins/easyzoom.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/plugins/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/plugins/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/plugins/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/plugins/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Berkshire+Swash&family=Lora:ital@1&family=Mystery+Quest&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Berkshire+Swash&family=Mystery+Quest&display=swap" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-202071959-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-202071959-1');
    </script>

    <script>(function(w, d) { 
      w.botId = '42'
      w.botTypeId = '1'
      w.baseApiUrl = 'https://api.queuebot.in/api'
      w.appid = '15a9da34-823b-4a70-9e44-e7649cd76561'
      w.baseUrl = 'https://app.queuebot.in/'
      w.customerId = '14'
     var header = d.head || d.getElementsByTagName("head")[0]; 
     var scriptTag = d.createElement("script"); 
     scriptTag.setAttribute("type", "text/javascript"); 
     scriptTag.setAttribute("src","https://app.queuebot.in/static/js/page/botmainscript.js"); 
     header.appendChild(scriptTag);
     })(window, document);
    </script>

</head>
<body class="cnt-home">
  <div id="pageloader">
    <img src="{{ asset('frontend/assets/images/loader.gif') }}" alt="processing..." />
    <div>Please wait...We are processing your order!</div>
 </div>
<!-- ============================================== HEADER ============================================== -->
<div class="main-wrapper">
@include('seller.sellerbody.header')

<!-- ============================================== HEADER : END ============================================== -->
@yield('content')
<!-- /#top-banner-and-menu --> 
<div id="loading" style="display:none"><img src={{ asset('frontend/assets/images/loader.gif') }}></div>
<!-- ============================================================= FOOTER ============================================================= -->
@include('seller.sellerbody.footer')

</div>
      <!-- Modal -->
      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <div>QUICK VIEW</div>
              <button type="button" class="btn-close" data-bs-dismiss="modal" id="closeModel" aria-label="Close">
                <span aria-hidden="true"></span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-lg-5 col-md-6 col-12 col-sm-12">
                  <div class="tab-content quickview-big-img">
                    <div id="pro-1" class="tab-pane fade show active">
                      <img src="" id="pimage" alt="">
                    </div>
                    
                  </div>
                  <div class="quickview-wrap mt-15">
                    <div class="quickview-slide-active nav nav-style-6">
                      <a class="active" data-bs-toggle="tab" href="#pro-1">
                        <img src="" alt="" id="">
                      </a>
                      
                    </div>
                  </div>
                </div>
                <div class="col-lg-7 col-md-6 col-12 col-sm-12">
                  <div class="product-details-content quickview-content">
                    <h2><span id="product_name"></span></h2>
                    <p id="pshort"></p>
                      <div class="pro-details-price">
                        <span class="new-price">₹<span id="pprice"></span></span>
                        <span class="old-price">₹<span id="oldprice"></span></span>
                      </div>
                      <div>
                        <strong>Customer Price</strong>
                        <span><s id="pcus_price"></s></span>
                      </div>
                      <input type="hidden" id="qty" name="qty" min="1" value="">
                      <div class="pro-details-quality">
                        <span>Available Quantity: <strong id="avaibquaty"></strong></span>
                      </div>
                      
                  <div class="pro-details-color-wrap" id="colorDiv"><br>
                      <span>Color:</span>
                      <div class="pro-details-color-content">
                          <ul>
                              <li><a id="pcolor"></a></li>
                          </ul>
                      </div>
                  </div>               
                  <div class="pro-details-quality">
                      <span>Product SKU: <a id="psku"></a></span>
                  </div>
                  <div class="product-details-meta">
                      <ul>
                          <li><span>Categories:</span> <a id="pcategory"></a></li>
                          <li><span>Tag: </span> <a id="ptags"></a></li>
                      </ul>
                  </div>
                    <div class="pro-details-action-wrap">
                      <div>
                        <input type="hidden" id="product_id">
                          <button type="submit" class="btn btn-flat btn-dark" data-variant-id="" title="Add to Cart" onclick="addToCart(this)" >Add To Cart</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Modal end -->

<!-- ============================================================= FOOTER : END============================================================= --> 

<!-- All JS is here
============================================ -->
@yield('script')
<script src="{{ asset('frontend/assets/js/vendor/modernizr-3.11.7.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/vendor/jquery-v3.6.0.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/vendor/jquery-migrate-v3.3.2.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/vendor/popper.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/vendor/bootstrap.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/plugins/slick.js') }}"></script>
<script src="{{ asset('frontend/assets/js/plugins/jquery.syotimer.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/plugins/jquery.instagramfeed.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/plugins/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/plugins/wow.js') }}"></script>
<script src="{{ asset('frontend/assets/js/plugins/jquery-ui-touch-punch.js') }}"></script>
<script src="{{ asset('frontend/assets/js/plugins/jquery-ui.js') }}"></script>
<script src="{{ asset('frontend/assets/js/plugins/magnific-popup.js') }}"></script>
<script src="{{ asset('frontend/assets/js/plugins/sticky-sidebar.js') }}"></script>
<script src="{{ asset('frontend/assets/js/plugins/easyzoom.js') }}"></script>
<script src="{{ asset('frontend/assets/js/plugins/scrollup.js') }}"></script>
<script src="{{ asset('frontend/assets/js/plugins/ajax-mail.js') }}"></script>
<!-- Main JS -->
<script src="{{ asset('frontend/assets/js/main.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="{{ asset('js/sort.js') }}"></script>
<script src="{{ asset('frontend/assets/js/cart.js?v=1') }}"></script>

  
  <script>
   @if (Session::has('message'))
   var type = "{{ Session::get('alert-type', 'info') }}"
   switch(type){
      case 'info':
      toastr.info(" {{ Session::get('message') }} ");
      toastr.options =
        {
          "closeButton" : true,
          "progressBar" : true
        }
      break;
      case 'success':
      toastr.success(" {{ Session::get('message') }} ");
      toastr.options =
        {
          "closeButton" : true,
          "progressBar" : true
        }
      break;
      case 'warning':
      toastr.warning(" {{ Session::get('message') }} ");
      toastr.options =
        {
          "closeButton" : true,
          "progressBar" : true
        }
      break;
      case 'error':
      toastr.error(" {{ Session::get('message') }} ");
      toastr.options =
        {
          "closeButton" : true,
          "progressBar" : true
        }
      break; 
   } @endif
  </script>
  
  <!--  //////////////// =========== End Js ================= ////  -->


<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function productView(id) {
        $.ajax({
            type: 'GET',
            url: '/product/view/modal/' + id,
            dataType: 'json',
            success: function(data) {
                $('#product_name').text(data.product.product_name);
                $('#pshort').text(data.product.short_description);
                $('#psku').text(data.product.product_sku);
                if (data.product.color) {
                    $('#pcolor').attr('style', 'background-color:' + data.product.color.color_code);
                    $('#colorDiv').show();
                } else {
                    $('#colorDiv').hide();
                }
                $('#pcus_price').text(data.product.product_discount);
                $('#avaibquaty').text(data.product.current_stock);
                $('#pimage').attr('src', '/' + data.product.product_image);
                $('#pcategory').text(data.product.category.category_name);
                $('#ptags').text(data.product.tags);
                $('#product_id').val(id);
                $('#qty').val('1');
                $('#pprice').text(data.product.seller_discount);
                $('#oldprice').text(data.product.seller_price);
            }
        })

    }
</script>
</body>
</html>
