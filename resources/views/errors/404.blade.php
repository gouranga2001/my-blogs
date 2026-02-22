<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Page Not Found</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap 5 CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            background: #f8fafc;
        }
    </style>
</head>
<body>

<div class="container min-vh-100 d-flex align-items-center">
    <div class="row w-100 align-items-center">

        <div class="col-md-6 text-center mb-4 mb-md-0">
            <img src="{{ asset('404.svg') }}" 
                 alt="404 Illustration" 
                 class="img-fluid"
                 style="max-height: 400px;">
        </div>

        <div class="col-md-6 text-center text-md-start">
            <h2 class="mb-3 text-primary">Oops! Page Not Found</h4>
            <p class="text-muted mb-4">
                The page you're looking for doesn't exist or has been moved.
            </p>

            <a href="{{ url('/') }}" class="btn btn-primary px-4 py-2">
                Go Back Home
            </a>
        </div>

    </div>
</div>

</body>
</html>