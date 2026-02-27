<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo Cáo Theo Ngày</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 10px; }
        .container { max-width: 800px; margin: 0 auto; }
        
        .header { background: linear-gradient(to right, #27ae60, #2ecc71); color: white; padding: 15px; margin-bottom: 10px; }
        .header h1 { font-size: 20px; margin-bottom: 3px; }
        .header .subtitle { font-size: 13px; opacity: 0.9; }
        
        .back-btn { display: inline-block; background: white; color: #333; padding: 10px 15px; text-decoration: none; margin-bottom: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        
        .stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-bottom: 10px; }
        .stat-card { background: white; padding: 15px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .stat-card .label { font-size: 12px; color: #7f8c8d; margin-bottom: 5px; }
        .stat-card .value { font-size: 22px; font-weight: bold; color: #27ae60; }
        
        .filter-bar { background: white; padding: 10px; margin-bottom: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .filter-bar form { display: flex; gap: 10px; }
        .filter-bar input[type="date"] { flex: 1; padding: 12px; border: 1px solid #ddd; font-size: 16px; }
        .filter-bar button { padding: 12px 20px; background: #27ae60; color: white; border: none; cursor: pointer; font-size: 16px; }
        
        .debt-cards { display: flex; flex-direction: column; gap: 10px; }
        .debt-card { background: white; padding: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .debt-card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
        .debt-card-name { font-size: 16px; font-weight: 600; color: #333; }
        .debt-card-quay { font-size: 14px; color: #3498db; background: #ebf5fb; padding: 3px 10px; font-weight: 500; }
        .debt-card-money { font-size: 20px; font-weight: bold; color: #27ae60; margin-bottom: 5px; }
        .debt-card-info { font-size: 12px; color: #666; }
        .debt-card-info .month-badge { display: inline-block; background: #fff3cd; color: #856404; padding: 2px 8px; border-radius: 3px; font-weight: 500; margin-left: 5px; }
        .debt-card-time { font-size: 11px; color: #999; margin-top: 5px; }
        
        .empty { text-align: center; padding: 50px 20px; color: #999; background: white; }
        
        .alert { padding: 10px; margin-bottom: 10px; font-size: 13px; }
        .alert-success { background: #d5f4e6; border-left: 3px solid #27ae60; color: #27ae60; }
        .alert-error { background: #fee2e2; border-left: 3px solid #dc2626; color: #dc2626; }
        
        @media (min-width: 768px) {
            body { padding: 20px; }
            .header { padding: 20px; }
            .header h1 { font-size: 24px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('debt-collections.index') }}" class="back-btn">← Quay lại</a>
        
        <div class="header">
            <h1>📊 Báo Cáo Thu Tiền</h1>
            <div class="subtitle">Xem chi tiết các khoản đã thu</div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="filter-bar">
            <form method="GET" action="{{ route('debt-collections.bao-cao-ngay') }}">
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 8px;">
                    <span style="font-size: 13px; color: #666; white-space: nowrap;">Lọc tháng nợ:</span>
                    <select name="thang_no" style="flex: 1; padding: 12px; border: 1px solid #ddd; font-size: 16px;">
                        <option value="">Tất cả</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ ($thangNo ?? '') == $i ? 'selected' : '' }}>Tháng {{ $i }}</option>
                        @endfor
                    </select>
                    <select name="nam_no" style="flex: 1; padding: 12px; border: 1px solid #ddd; font-size: 16px;">
                        @for($y = 2024; $y <= 2030; $y++)
                            <option value="{{ $y }}" {{ ($namNo ?? now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                
                {{-- <input type="date" name="ngay" value="{{ $ngay ?? '' }}" placeholder="Chọn ngày" style="width: 100%; padding: 12px; border: 1px solid #ddd; font-size: 16px; margin-bottom: 8px;"> --}}
                
                <button type="submit" style="width: 100%; padding: 12px; background: #27ae60; color: white; border: none; cursor: pointer; font-size: 16px;">Xem</button>
            </form>
        </div>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="filter-bar">
            <form method="GET" action="{{ route('debt-collections.bao-cao-ngay') }}">
                <input type="date" name="ngay" value="{{ $ngay }}" required>
                <button type="submit">Xem</button>
            </form>
        </div>

        <div class="stats">
            <div class="stat-card">
                <div class="label">Số lượng đã thu</div>
                <div class="value">{{ $soLuong }}</div>
            </div>
            <div class="stat-card">
                <div class="label">Tổng tiền</div>
                <div class="value">{{ number_format($tongTien, 0) }}đ</div>
            </div>
        </div>

        <div style="background: #e8f5e9; padding: 10px; margin-bottom: 10px; text-align: center; font-size: 14px; color: #2e7d32;">
            📅 Báo cáo ngày: <strong>{{ \Carbon\Carbon::parse($ngay)->format('d/m/Y') }}</strong>
            @if($thangNo)
                - Nợ tháng: <strong>{{ $thangNo }}/{{ $namNo }}</strong>
            @endif
        </div>

        <div class="debt-cards">
            @forelse($danhSach as $item)
            <div class="debt-card">
                <div class="debt-card-header">
                    <div class="debt-card-name">{{ $item->ho_ten }}</div>
                    <div class="debt-card-quay">{{ $item->so_quay }}</div>
                </div>
                <div class="debt-card-money">{{ number_format($item->so_tien, 0) }}đ</div>
                <div class="debt-card-info">
                    Nợ tháng: <strong>{{ $item->thang }}/{{ $item->nam }}</strong>
                    @if($item->thang != now()->month || $item->nam != now()->year)
                        <span class="month-badge">Truy thu</span>
                    @endif
                </div>
                <div class="debt-card-time">
                    ⏰ Thu lúc: {{ $item->ngay_thu_thuc_te->format('H:i - d/m/Y') }}
                </div>
            </div>
            @empty
            <div class="empty">
                <div>Không có dữ liệu thu trong ngày {{ \Carbon\Carbon::parse($ngay)->format('d/m/Y') }}</div>
            </div>
            @endforelse
        </div>
    </div>

    <div style="text-align: center; padding: 20px; color: #999; font-size: 12px;">
        COPYRIGHT BY HAILAM
    </div>
</body>
</html>
