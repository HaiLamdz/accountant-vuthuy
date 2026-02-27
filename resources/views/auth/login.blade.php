<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: Arial, sans-serif; 
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 380px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .login-header .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        
        .login-header h1 {
            font-size: 22px;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .login-header p {
            color: #7f8c8d;
            font-size: 13px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: #555;
            font-weight: 500;
            font-size: 13px;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #27ae60;
        }
        
        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(to right, #27ae60, #2ecc71);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }
        
        .btn-login:hover {
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.4);
            transform: translateY(-1px);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .alert {
            padding: 10px 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 13px;
        }
        
        .alert-success {
            background: #d5f4e6;
            color: #27ae60;
            border-left: 3px solid #27ae60;
        }
        
        .alert-error {
            background: #fee2e2;
            color: #dc2626;
            border-left: 3px solid #dc2626;
        }
        
        .login-footer {
            margin-top: 20px;
            text-align: center;
            font-size: 11px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="icon">🏪</div>
            <h1>Quản Lý Thu Giá Dịch Vụ</h1>
            <p>Đăng nhập để tiếp tục</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            
            <div class="form-group">
                <label>Tên đăng nhập</label>
                <input type="text" name="username" value="{{ old('username') }}" placeholder="Nhập tên đăng nhập" required autofocus>
                @error('username')
                    <small style="color: #dc2626; font-size: 12px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" placeholder="Nhập mật khẩu" required>
                @error('password')
                    <small style="color: #dc2626; font-size: 12px;">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn-login">Đăng nhập</button>
        </form>

        <div class="login-footer">
            COPYRIGHT BY HAILAM
        </div>
    </div>
</body>
</html>
