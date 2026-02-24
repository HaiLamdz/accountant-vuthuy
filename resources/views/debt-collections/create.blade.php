<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm khoản thu nợ</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; padding: 15px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; }
        .card { background: white; padding: 15px; }
        h1 { color: #2c3e50; margin-bottom: 15px; font-size: 18px; padding-bottom: 10px; border-bottom: 2px solid #3498db; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 500; color: #333; font-size: 13px; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; font-size: 14px; }
        .form-group input:focus, .form-group select:focus { outline: none; border-color: #3498db; }
        .error { color: #dc2626; font-size: 12px; margin-top: 3px; }
        .required { color: #dc2626; }
        .actions { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 20px; }
        .btn { padding: 12px; border: none; cursor: pointer; font-size: 14px; text-align: center; text-decoration: none; display: block; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .btn-primary { background: #3498db; color: white; }
        .btn-primary:active { background: #2980b9; box-shadow: 0 1px 2px rgba(52,152,219,0.3); transform: translateY(1px); }
        .btn-secondary { background: white; color: #333; }
        .btn-secondary:active { background: #f0f0f0; box-shadow: 0 1px 2px rgba(0,0,0,0.1); transform: translateY(1px); }
        
        @media (min-width: 768px) {
            body { padding: 20px; }
            .card { padding: 20px; }
            h1 { font-size: 20px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Thêm khoản thu nợ</h1>
            
            <form method="POST" action="{{ route('debt-collections.store') }}">
                @csrf
                
                <div class="form-group">
                    <label>Họ tên <span class="required">*</span></label>
                    <input type="text" name="ho_ten" value="{{ old('ho_ten') }}" placeholder="Nhập họ tên" required>
                    @error('ho_ten')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Số quầy <span class="required">*</span></label>
                    <input type="text" name="so_quay" value="{{ old('so_quay') }}" placeholder="VD: Q01" required>
                    @error('so_quay')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Số tiền (VNĐ) <span class="required">*</span></label>
                    <input type="number" name="so_tien" value="{{ old('so_tien') }}" min="0" step="1000" placeholder="500000" required>
                    @error('so_tien')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Ngày thu dự kiến <span class="required">*</span></label>
                    <input type="date" name="ngay_thu_du_kien" value="{{ old('ngay_thu_du_kien', now()->format('Y-m-d')) }}" required>
                    @error('ngay_thu_du_kien')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Tháng <span class="required">*</span></label>
                    <select name="thang" required>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ old('thang', now()->month) == $i ? 'selected' : '' }}>
                                Tháng {{ $i }}
                            </option>
                        @endfor
                    </select>
                    @error('thang')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Năm <span class="required">*</span></label>
                    <input type="number" name="nam" value="{{ old('nam', now()->year) }}" min="2020" max="2030" required>
                    @error('nam')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-primary">Lưu</button>
                    <a href="{{ route('debt-collections.index') }}" class="btn btn-secondary">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
