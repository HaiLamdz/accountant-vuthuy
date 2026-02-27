<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm khoản thu giá dịch vụ</title>
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
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
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
            <h1>Thêm khoản thu giá dịch vụ</h1>
            
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
                    <input type="text" id="so_tien_display" placeholder="500,000" style="font-size: 16px;">
                    <input type="hidden" name="so_tien" id="so_tien" value="{{ old('so_tien') }}" required>
                    @error('so_tien')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row">
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
                        <select name="nam" required>
                            @for($y = 2024; $y <= 2030; $y++)
                                <option value="{{ $y }}" {{ old('nam', now()->year) == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                        @error('nam')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Hidden input cho ngay_thu_du_kien (set ngày 1 của tháng) -->
                <input type="hidden" name="ngay_thu_du_kien" id="ngay_thu_du_kien" value="{{ old('ngay_thu_du_kien') }}">

                <div class="actions">
                    <button type="submit" class="btn btn-primary">Lưu</button>
                    <a href="{{ route('debt-collections.index') }}" class="btn btn-secondary">Quay lại</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const displayInput = document.getElementById('so_tien_display');
        const hiddenInput = document.getElementById('so_tien');
        const thangSelect = document.querySelector('select[name="thang"]');
        const namSelect = document.querySelector('select[name="nam"]');
        const ngayThuInput = document.getElementById('ngay_thu_du_kien');

        // Format số khi nhập
        displayInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d]/g, '');
            
            if (value) {
                e.target.value = parseInt(value).toLocaleString('vi-VN');
                hiddenInput.value = value;
            } else {
                e.target.value = '';
                hiddenInput.value = '';
            }
        });

        // Tự động set ngày thu dự kiến = ngày 1 của tháng
        function updateNgayThu() {
            const thang = thangSelect.value.padStart(2, '0');
            const nam = namSelect.value;
            ngayThuInput.value = `${nam}-${thang}-01`;
        }

        thangSelect.addEventListener('change', updateNgayThu);
        namSelect.addEventListener('change', updateNgayThu);

        // Set giá trị ban đầu
        updateNgayThu();

        // Khôi phục giá trị cũ nếu có lỗi
        @if(old('so_tien'))
            displayInput.value = parseInt('{{ old('so_tien') }}').toLocaleString('vi-VN');
            hiddenInput.value = '{{ old('so_tien') }}';
        @endif
    </script>

    <div style="text-align: center; padding: 20px; color: #999; font-size: 12px;">
        COPYRIGHT BY HAILAM
    </div>
</body>
</html>
