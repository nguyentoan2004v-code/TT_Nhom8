<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập Admin - Báo Đốm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-orange: #ff6b00;
            --dark-navy: #2c3e50;
            --bg-gray: #f7f7f7;
            --white: #ffffff;
        }

        body {
            background-color: var(--bg-gray);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Roboto', 'Segoe UI', sans-serif;
            padding-top: 60px;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 0 15px;
        }

        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-top: 4px solid var(--primary-orange);
        }

        .card-body {
            padding: 40px;
            background: var(--white);
        }

        .card-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark-navy);
            margin-bottom: 30px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .card-title i {
            color: var(--primary-orange);
            font-size: 32px;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-navy);
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-label i {
            color: var(--primary-orange);
            margin-right: 6px;
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 12px 14px;
            font-size: 14px;
            transition: all 0.3s ease;
            background-color: #fafafa;
        }

        .form-control::placeholder {
            color: #999;
        }

        .form-control:focus {
            border-color: var(--primary-orange);
            background-color: var(--white);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 0, 0.15);
        }

        .btn-login {
            background: var(--primary-orange);
            border: none;
            border-radius: 6px;
            padding: 12px 20px;
            font-weight: 600;
            font-size: 15px;
            color: var(--white);
            transition: all 0.3s ease;
            width: 100%;
            cursor: pointer;
        }

        .btn-login:hover {
            background: #e55a00;
            box-shadow: 0 4px 12px rgba(255, 107, 0, 0.3);
            color: var(--white);
        }

        .btn-login:active {
            background: #cc4d00;
        }

        .btn-back {
            border: 2px solid var(--primary-orange);
            color: var(--primary-orange);
            background: var(--white);
            border-radius: 6px;
            padding: 10px 20px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            cursor: pointer;
        }

        .btn-back:hover {
            background: var(--primary-orange);
            color: var(--white);
            box-shadow: 0 4px 12px rgba(255, 107, 0, 0.2);
        }

        .btn-back i {
            font-size: 14px;
        }

        .alert {
            border-radius: 6px;
            border: none;
            background-color: #ffe5cc;
            color: #cc4d00;
            border-left: 4px solid var(--primary-orange);
            margin-bottom: 20px;
        }

        .alert i {
            margin-right: 8px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .btn-group-login {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }

        .btn-group-login .btn-login {
            flex: 1;
        }

        .btn-group-login .btn-back {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-user-shield"></i>
                    Đăng nhập Admin
                </h5>

                @if($errors->has('login'))
                    <div class="alert" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $errors->first('login') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.post') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-envelope"></i> Email
                        </label>
                        <input type="email" name="username" class="form-control" placeholder="Nhập email của bạn" value="{{ old('username') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-lock"></i> Mật khẩu
                        </label>
                        <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
                    </div>

                    <div class="btn-group-login">
                        <button type="submit" class="btn btn-login">
                            <i class="fas fa-sign-in-alt"></i> Đăng nhập
                        </button>
                        <a href="{{ url('/') }}" class="btn-back">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
