<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thu Nợ Hôm Nay</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 10px; }
        .container { max-width: 800px; margin: 0 auto; }
        
        /* Header */
        .header { background: linear-gradient(to right, #f0f4f8, #e8eef3); color: #2c3e50; padding: 15px; margin-bottom: 10px; border-bottom: 2px solid #3498db; }
        .header h1 { font-size: 20px; margin-bottom: 3px; }
        .header .date { font-size: 13px; opacity: 0.7; color: #34495e; }
        
        /* Stats */
        .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 10px; }
        .stat-card { background: white; padding: 12px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .stat-card .label { font-size: 11px; color: #7f8c8d; margin-bottom: 3px; }
        .stat-card .value { font-size: 18px; font-weight: bold; color: #2c3e50; }
        .stat-card:nth-child(1) .value { color: #3498db; }
        .stat-card:nth-child(2) .value { color: #27ae60; }
        .stat-card:nth-child(3) .value { color: #e67e22; }
        
        /* Filter */
        .filter-bar { background: white; padding: 10px; margin-bottom: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .filter-bar input[type="date"] { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #ddd; 
            font-size: 16px; 
            margin-bottom: 8px;
            -webkit-appearance: none;
            appearance: none;
            background: white;
        }
        .filter-bar select { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #ddd; 
            font-size: 16px; 
            background: white;
            -webkit-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
        }
        
        /* Actions */
        .actions-bar { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-bottom: 10px; }
        .btn { padding: 12px; border: none; cursor: pointer; font-size: 14px; text-align: center; text-decoration: none; display: block; background: white; color: #333; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .btn:active { background: #f0f0f0; box-shadow: 0 1px 2px rgba(0,0,0,0.1); transform: translateY(1px); }
        
        /* Cards */
        .debt-cards { display: flex; flex-direction: column; gap: 10px; }
        .debt-card { background: white; padding: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .debt-card.completed { opacity: 0.7; background: #f8fffe; }
        .debt-card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .debt-card-name { font-size: 16px; font-weight: 600; color: #333; }
        .debt-card-quay { font-size: 14px; color: #3498db; background: #ebf5fb; padding: 3px 10px; font-weight: 500; }
        .debt-card-money { font-size: 22px; font-weight: bold; color: #27ae60; margin-bottom: 8px; }
        .debt-card-info { font-size: 12px; color: #666; margin-bottom: 10px; }
        .debt-card-status { display: inline-block; padding: 4px 10px; font-size: 12px; font-weight: 500; margin-bottom: 10px; }
        .status-completed { background: #d5f4e6; color: #27ae60; }
        .status-pending { background: #fff3cd; color: #e67e22; }
        .debt-card-actions { display: grid; grid-template-columns: 3fr 1fr; gap: 10px; }
        .btn-thu { background: #3498db; color: white; padding: 10px; border: none; cursor: pointer; font-size: 14px; box-shadow: 0 2px 4px rgba(52,152,219,0.3); }
        .btn-thu:active { background: #2980b9; box-shadow: 0 1px 2px rgba(52,152,219,0.3); transform: translateY(1px); }
        .btn-thu:disabled { background: #95a5a6; cursor: not-allowed; box-shadow: none; }
        .btn-delete { background: white; color: #333; padding: 10px; border: none; cursor: pointer; font-size: 14px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .btn-delete:active { background: #f0f0f0; box-shadow: 0 1px 2px rgba(0,0,0,0.1); transform: translateY(1px); }
        
        /* Alert */
        .alert { padding: 10px; margin-bottom: 10px; font-size: 13px; background: #d5f4e6; border-left: 3px solid #27ae60; color: #27ae60; }
        
        /* Empty */
        .empty { text-align: center; padding: 50px 20px; color: #999; background: white; }
        .empty-text { font-size: 14px; }
        
        /* Modal */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
        .modal.active { display: flex; align-items: center; justify-content: center; padding: 20px; }
        .modal-content { background: white; padding: 15px; width: 100%; max-width: 400px; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #ddd; }
        .modal-title { font-size: 16px; font-weight: 600; }
        .modal-close { font-size: 24px; cursor: pointer; color: #999; line-height: 1; }
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; margin-bottom: 5px; font-size: 13px; color: #666; }
        .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; font-size: 14px; }
        
        @media (min-width: 768px) {
            body { padding: 20px; }
            .header { padding: 20px; }
            .header h1 { font-size: 24px; }
            .actions-bar { grid-template-columns: repeat(4, 1fr); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Thu Nợ</h1>
            <div class="date">{{ \Carbon\Carbon::parse($ngay)->format('d/m/Y') }}</div>
        </div>

        @if(session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
            <div class="alert" style="border-left-color: #dc2626; background: #fee2e2; color: #dc2626;">{{ session('error') }}</div>
        @endif

        <div class="stats">
            <div class="stat-card">
                <div class="label">Tổng thu ngày này</div>
                <div class="value">{{ number_format($tongThuNgay / 1000, 0) }}K</div>
            </div>
            <div class="stat-card">
                <div class="label">Đã thu</div>
                <div class="value">{{ $soLuongDaThu }}</div>
            </div>
            <div class="stat-card">
                <div class="label">Chưa thu</div>
                <div class="value">{{ $soLuongChuaThu }}</div>
            </div>
        </div>

        <div class="filter-bar">
            <form method="GET" action="{{ route('debt-collections.index') }}" id="filterForm">
                <input type="hidden" name="thang" value="{{ $thang }}">
                <input type="hidden" name="nam" value="{{ $nam }}">
                <input type="date" name="ngay" value="{{ $ngay }}" onchange="this.form.submit()">
                <select name="trang_thai" onchange="this.form.submit()">
                    <option value="">Tất cả trạng thái</option>
                    <option value="chua_thu" {{ $trangThai == 'chua_thu' ? 'selected' : '' }}>Chưa thu</option>
                    <option value="da_thu" {{ $trangThai == 'da_thu' ? 'selected' : '' }}>Đã thu</option>
                </select>
            </form>
        </div>

        <div class="actions-bar">
            <a href="{{ route('debt-collections.create') }}" class="btn">+ Thêm mới</a>
            <button onclick="openModal()" class="btn">Thống kê</button>
            <a href="{{ route('debt-collections.export', ['thang' => $thang, 'nam' => $nam]) }}" class="btn">Export</a>
            <button onclick="document.getElementById('fileInput').click()" class="btn">Import</button>
        </div>

        <form method="POST" action="{{ route('debt-collections.import') }}" enctype="multipart/form-data" id="importForm" style="display: none;">
            @csrf
            <input type="file" name="file" id="fileInput" accept=".xlsx,.xls,.csv" onchange="this.form.submit()">
        </form>

        <div class="debt-cards">
            @forelse($danhSach as $item)
            <div class="debt-card {{ $item->trang_thai == 'da_thu' ? 'completed' : '' }}">
                <div class="debt-card-header">
                    <div class="debt-card-name">{{ $item->ho_ten }}</div>
                    <div class="debt-card-quay">{{ $item->so_quay }}</div>
                </div>
                <div class="debt-card-money">{{ number_format($item->so_tien, 0, ',', '.') }}đ</div>
                <div class="debt-card-info">
                    Tháng {{ $item->thang }}/{{ $item->nam }}
                </div>
                @if($item->trang_thai == 'da_thu')
                    <div class="debt-card-status status-completed">✓ Đã thu - {{ $item->ngay_thu_thuc_te->format('d/m/Y') }}</div>
                @else
                    <div class="debt-card-status status-pending">⏳ Chưa thu</div>
                @endif
                <div class="debt-card-actions">
                    <form method="POST" action="{{ route('debt-collections.thu', $item) }}">
                        @csrf
                        <button type="submit" class="btn-thu" {{ $item->trang_thai == 'da_thu' ? 'disabled' : '' }}>
                            {{ $item->trang_thai == 'da_thu' ? 'Đã thu rồi' : 'Đã thu tiền' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('debt-collections.destroy', $item) }}" onsubmit="return confirm('Xóa?')">
                        @csrf
                        <button type="submit" class="btn-delete">Xóa</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="empty">
                <div class="empty-text">Không có dữ liệu</div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Modal -->
    <div class="modal" id="statsModal" onclick="closeModal(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="modal-header">
                <div class="modal-title">Xem thống kê</div>
                <span class="modal-close" onclick="closeModal()">&times;</span>
            </div>
            <form method="GET" action="{{ route('debt-collections.index') }}">
                <div class="form-group">
                    <label>Chọn tháng</label>
                    <select name="thang">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $thang == $i ? 'selected' : '' }}>Tháng {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="form-group">
                    <label>Chọn năm</label>
                    <select name="nam">
                        @for($y = 2024; $y <= 2030; $y++)
                            <option value="{{ $y }}" {{ $nam == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="btn-thu" style="width: 100%;">Xem</button>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('statsModal').classList.add('active');
        }
        
        function closeModal(event) {
            if (!event || event.target.id === 'statsModal') {
                document.getElementById('statsModal').classList.remove('active');
            }
        }
    </script>
</body>
</html>
