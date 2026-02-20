<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa, #eef2ff);
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        }

        .auth-card {
            border-radius: 20px;
            background: white;
            box-shadow: 0 20px 50px rgba(13, 110, 253, 0.08);
        }

        .form-control {
            border-radius: 12px;
            padding: 12px;
            font-size: 15px;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
        }

        .btn-primary {
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
        }

        .subtitle {
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>

<body>

<div class="container-fluid min-vh-100 d-flex align-items-center py-4 px-3 px-md-0">
    <div class="row w-100 align-items-center justify-content-center">

        <!-- Illustration (desktop only) -->
        <div class="col-md-6 d-none d-md-flex justify-content-center align-items-center">
            <img src="{{ asset('login.svg') }}"
                 alt="Login Illustration"
                 class="img-fluid"
                 style="max-width: 420px;">
        </div>

        <!-- Form -->
        <div class="col-12 col-md-6 d-flex justify-content-center">
            <div class="card auth-card w-100 p-4 p-md-5" style="max-width: 420px;">

                <div class="text-center mb-4">
                    <h3 class="text-primary fw-semibold">Welcome Back 👋</h3>
                    <div class="subtitle">Please login to your account</div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="/login">
                    @csrf

                    <div class="mb-3">
                        <input type="email" name="email"
                               class="form-control"
                               placeholder="Email Address"
                               required>
                    </div>

                    <div class="mb-3">
                        <input type="password" name="password"
                               class="form-control"
                               placeholder="Password"
                               required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-2">
                        Login
                    </button>

                    <div class="text-center mt-4">
                        <small>
                            Don't have an account?
                            <a href="/register" class="text-primary fw-semibold">Register</a>
                        </small>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

</body>
</html>
