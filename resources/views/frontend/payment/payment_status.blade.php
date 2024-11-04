<!DOCTYPE html>
<html lang="en">

<head>
    <title>Payment Status</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <div class="container mt-3">
        <div
            style="margin: auto;width: 50%;padding: 70px 0;position: absolute;top: 25%;left: 25%;font-family: sans-serif;font-size: 20px;">
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong>Payment Failed!</strong>
            </div>
            @if (Auth::user()->userrole_id == 1)
                <div class="text-center"><a href='/'>Go to home</a></div>
            @else
                <div class="text-center"><a href={{ route('seller.home') }}>Go to home</a></div>
            @endif

        </div>
    </div>

</body>

</html>
