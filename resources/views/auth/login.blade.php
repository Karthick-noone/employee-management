<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Employee Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6f9;
        }
        .login-box {
            margin-top: 100px;
            padding: 30px;
            border-radius: 15px;
            background: white;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>


<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="col-md-4 login-box">
        <h3 class="text-center mb-4">Login</h3>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" placeholder="Email" class="form-control" required autofocus>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" placeholder="Password" class="form-control" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
