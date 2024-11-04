<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Admin | Nithitex India's No 1 Online Saree Shop</title>
    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/core/core.css') }}">
    <link rel="stylesheet"
        href="{{ asset('backend/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/fonts/feather-font/css/iconfont.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/demo_1/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.png') }}" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/date/daterangepicker.css') }}">
    {{-- font-awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>

<body class="sidebar-dark">
    <div id="pageloader">
        <img src="{{ asset('frontend/assets/images/loader.gif') }}" alt="processing..." />
        <div>Updating status please wait...</div>
    </div>
    <div class="main-wrapper">
        @include('admin.body.navbar')
        @hasrole('Super Admin')
            @include('admin.body.sidebar')
        @else
            @include('admin.body.sidebar_for_roles')
        @endhasrole
        <div class="page-wrapper">
            @include('admin.body.head')
            <div>
                @yield('admin')
            </div>
            @include('admin.body.footer')
        </div>
    </div>

    <script src="{{ asset('backend/assets/vendors/core/core.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/chartjs/Chart.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/jquery.flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/jquery.flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/progressbar.js/progressbar.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/template.js') }}"></script>
    <script src="{{ asset('backend/assets/js/dashboard.js') }}"></script>
    <script src="{{ asset('backend/assets/js/datepicker.js') }}"></script>
    <script src="{{ asset('backend/assets/js/password_view.js') }}"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    {{-- <!-- Tinymce HTML Editor -->
    <script src="https://cdn.tiny.cloud/1/cg2n8uh91qt6u5gawmdq3vcvcpnf137kcsv7x2glk0mh0add/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script> --}}
    <!-- plugin js for this page -->
    <script src="{{ asset('backend/assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
    <!-- custom js for this page -->
    <script src="{{ asset('backend/assets/js/data-table.js') }}"></script>
    <!-- end custom js for this page -->
    <script type="text/javascript" src="{{ asset('backend/date/jquery.daterangepicker.js') }}"></script>
    <script src="{{ asset('backend/assets/js/products.js') }}"></script>
    <script src="{{ asset('js/coupon.js') }}"></script>
    <script src="{{ asset('js/loader.js') }}"></script>
    <script>
        @if (Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}"
            switch (type) {
                case 'info':
                    toastr.info(" {{ Session::get('message') }} ");
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true
                    }
                    break;
                case 'success':
                    toastr.success(" {{ Session::get('message') }} ");
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true
                    }
                    break;
                case 'warning':
                    toastr.warning(" {{ Session::get('message') }} ");
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true
                    }
                    break;
                case 'error':
                    toastr.error(" {{ Session::get('message') }} ");
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true
                    }
                    break;
            }
        @endif
    </script>
    <script src="{{ asset('backend/dist/js/code.js') }}"></script>
    <!-- CKEditor Script -->
    <script src="https://cdn.ckeditor.com/ckeditor5/38.1.0/classic/ckeditor.js"></script>

    <!-- Initialize CKEditor on a textarea -->
    <script>
        ClassicEditor
            .create(document.querySelector('textarea'), {
                toolbar: [
                    'undo', 'redo',
                    '|', 'heading',
                    '|', 'bold', 'italic', 'underline', 'strikethrough',
                    '|', 'link', 'imageUpload', 'mediaEmbed', 'blockQuote', 'insertTable',
                    '|', 'numberedList', 'bulletedList', 'indent', 'outdent',
                    '|', 'alignment', 'fontSize', 'fontFamily', 'highlight'
                ],
                image: {
                    toolbar: [
                        'imageTextAlternative', 'imageStyle:inline', 'imageStyle:block', 'imageStyle:side'
                    ]
                },
                table: {
                    contentToolbar: [
                        'tableColumn', 'tableRow', 'mergeTableCells'
                    ]
                }
            })
            .catch(error => {
                console.error(error);
            });
    </script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // tinymce.init({
        //     selector: 'textarea',
        //     plugins: 'anchor autolink charmap image link lists media searchreplace table visualblocks wordcount checklist',
        //     toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | checklist numlist bullist indent outdent',
        //     menubar: false,
        // });

        function mainThamUrl(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#mainThmb').attr('src', e.target.result).width(80).height(80);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script src="{{ asset('js/sort.js') }}"></script>

</body>

</html>
