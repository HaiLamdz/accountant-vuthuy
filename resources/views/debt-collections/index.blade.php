<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Thu Giá Dịch Vụ</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 10px; }
        .container { max-width: 800px; margin: 0 auto; }
        
        /* Header */
        .header { 
            background: linear-gradient(to right, #27ae60, #2ecc71); 
            color: white; 
            padding: 20px; 
            margin-bottom: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: relative;
        }
        .header h1 { font-size: 20px; margin-bottom: 3px; color: white; }
        .header .date { font-size: 13px; opacity: 0.9; color: rgba(255,255,255,0.9); }
        .btn-logout {
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s;
            backdrop-filter: blur(10px);
        }
        .btn-logout:hover {
            background: rgba(255,255,255,0.3);
            border-color: rgba(255,255,255,0.5);
        }
        .btn-logout:active {
            transform: translateY(-50%) scale(0.95);
        }
        
        /* Tabs */
        .tabs { display: flex; gap: 5px; margin-bottom: 10px; background: white; padding: 5px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-radius: 8px; }
        .tab { flex: 1; padding: 12px; text-align: center; background: white; border: none; cursor: pointer; font-size: 14px; color: #666; text-decoration: none; display: block; border-radius: 6px; transition: all 0.3s; }
        .tab.active { background: linear-gradient(to right, #27ae60, #2ecc71); color: white; font-weight: 600; box-shadow: 0 2px 8px rgba(39, 174, 96, 0.4); }
        .tab:hover { background: #f5f5f5; }
        .tab.active:hover { background: linear-gradient(to right, #229954, #27ae60); }
        .tab .badge { display: inline-block; background: #e74c3c; color: white; padding: 2px 8px; border-radius: 12px; font-size: 11px; margin-left: 5px; font-weight: 600; }
        
        /* Stats */
        .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 10px; }
        .stat-card { background: white; padding: 15px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-radius: 8px; transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.12); }
        .stat-card .label { font-size: 11px; color: #7f8c8d; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-card .value { font-size: 18px; font-weight: bold; color: #2c3e50; }
        .stat-card:nth-child(1) { border-top: 3px solid #27ae60; }
        .stat-card:nth-child(1) .value { color: #27ae60; }
        .stat-card:nth-child(2) { border-top: 3px solid #2ecc71; }
        .stat-card:nth-child(2) .value { color: #2ecc71; }
        .stat-card:nth-child(3) { border-top: 3px solid #e67e22; }
        .stat-card:nth-child(3) .value { color: #e67e22; }
        
        /* Filter */
        .filter-bar { background: white; padding: 15px; margin-bottom: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-radius: 8px; }
        .filter-bar input[type="text"],
        .filter-bar input[type="date"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            font-size: 16px;
            margin-bottom: 8px;
            border-radius: 6px;
            background: white;
        }
        .filter-bar input[type="date"] {
            position: relative;
            color: #333;
        }
        .filter-bar input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            padding: 5px;
            opacity: 0.6;
        }
        .filter-bar input[type="date"]::before {
            content: attr(placeholder);
            position: absolute;
            color: #999;
            pointer-events: none;
        }
        .filter-bar input[type="date"]:focus::before,
        .filter-bar input[type="date"]:valid::before {
            display: none;
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
            margin-bottom: 8px;
        }
        .filter-bar .btn-search {
            width: 100%;
            padding: 12px;
            background: linear-gradient(to right, #27ae60, #2ecc71);
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 6px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .filter-bar .btn-search:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(39, 174, 96, 0.4);
        }
        .filter-bar .btn-search:active {
            transform: translateY(0);
        }
        
        .filter-bar .btn-reset {
            width: 100%;
            padding: 12px;
            background: white;
            color: #666;
            border: 2px solid #ddd;
            font-size: 16px;
            cursor: pointer;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.2s;
            text-decoration: none;
            display: block;
            text-align: center;
        }
        .filter-bar .btn-reset:hover {
            border-color: #27ae60;
            color: #27ae60;
            transform: translateY(-1px);
        }
        .filter-bar .btn-reset:active {
            transform: translateY(0);
        }
        
        .filter-buttons {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 10px;
        }
        
        /* Actions */
        .actions-bar { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-bottom: 10px; }
        .btn { padding: 12px; border: none; cursor: pointer; font-size: 14px; text-align: center; text-decoration: none; display: block; background: white; color: #333; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-radius: 8px; font-weight: 500; transition: all 0.2s; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .btn:active { transform: translateY(0); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        
        /* Cards */
        .debt-cards { display: flex; flex-direction: column; gap: 10px; }
        .debt-card { background: white; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-radius: 8px; transition: all 0.2s; }
        .debt-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.12); }
        .debt-card.completed { opacity: 0.7; background: #f8fffe; border-left: 4px solid #27ae60; }
        .debt-card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .debt-card-name { font-size: 16px; font-weight: 600; color: #333; }
        .debt-card-quay { font-size: 14px; color: #3498db; background: #ebf5fb; padding: 3px 10px; font-weight: 500; }
        .debt-card-money { font-size: 22px; font-weight: bold; color: #27ae60; margin-bottom: 8px; }
        .debt-card-info { font-size: 12px; color: #666; margin-bottom: 10px; }
        .debt-card-info .month-badge { display: inline-block; background: #fff3cd; color: #856404; padding: 2px 8px; border-radius: 3px; font-weight: 500; margin-left: 5px; }
        .debt-card-info .old-debt { background: #f8d7da; color: #721c24; }
        .debt-card-status { display: inline-block; padding: 4px 10px; font-size: 12px; font-weight: 500; margin-bottom: 10px; }
        .status-completed { background: #d5f4e6; color: #27ae60; }
        .status-pending { background: #fff3cd; color: #e67e22; }
        .debt-card-actions { display: grid; grid-template-columns: 3fr 1fr; gap: 10px; }
        .btn-thu { background: linear-gradient(to right, #27ae60, #2ecc71); color: white; padding: 12px; border: none; cursor: pointer; font-size: 14px; box-shadow: 0 2px 8px rgba(39, 174, 96, 0.3); border-radius: 6px; font-weight: 600; transition: all 0.2s; }
        .btn-thu:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(39, 174, 96, 0.4); }
        .btn-thu:active { transform: translateY(0); box-shadow: 0 2px 4px rgba(39, 174, 96, 0.3); }
        .btn-thu:disabled { background: #95a5a6; cursor: not-allowed; box-shadow: none; transform: none; }
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
            <h1>Quản Lý Thu Giá Dịch Vụ</h1>
            <div class="date">Tháng {{ $thang }}/{{ $nam }}</div>
            <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Bạn có chắc muốn đăng xuất?')">
                @csrf
                <button type="submit" class="btn-logout">
                    🚪 Đăng xuất
                </button>
            </form>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <a href="{{ route('debt-collections.index', ['thang' => $thang, 'nam' => $nam, 'view' => 'thang_nay']) }}" 
               class="tab {{ $viewMode === 'thang_nay' ? 'active' : '' }}">
                📅 Tháng này
            </a>
            <a href="{{ route('debt-collections.index', ['thang' => $thang, 'nam' => $nam, 'view' => 'no_cu']) }}" 
               class="tab {{ $viewMode === 'no_cu' ? 'active' : '' }}">
                ⚠️ Nợ cũ
                @if($noCuCount > 0)
                    <span class="badge">{{ $noCuCount }}</span>
                @endif
            </a>
        </div>

        @if(session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
            <div class="alert" style="border-left-color: #dc2626; background: #fee2e2; color: #dc2626;">{{ session('error') }}</div>
        @endif

        <div class="stats">
            <div class="stat-card">
                <div class="label">Tổng tiền</div>
                <div class="value">{{ number_format($tongTien, 0) }}đ</div>
            </div>
            <div class="stat-card">
                <div class="label">Đã thu</div>
                <div class="value">{{ number_format($tongTienDaThu, 0) }}đ</div>
            </div>
            <div class="stat-card">
                <div class="label">Chưa thu</div>
                <div class="value">{{ $soLuongChuaThu }}</div>
            </div>
        </div>

        <div class="filter-bar">
            <form method="GET" action="{{ route('debt-collections.index') }}" id="filterForm">
                <input type="hidden" name="view" value="{{ $viewMode }}">
                
                <input type="text" name="tim_kiem" placeholder="🔍 Tìm theo tên hoặc số quầy..." value="{{ $timKiem }}">
                
                @if($viewMode === 'thang_nay')
                <select name="thang">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $thang == $i ? 'selected' : '' }}>Tháng {{ $i }}</option>
                    @endfor
                </select>

                <select name="nam">
                    @for($y = 2024; $y <= 2030; $y++)
                        <option value="{{ $y }}" {{ $nam == $y ? 'selected' : '' }}>Năm {{ $y }}</option>
                    @endfor
                </select>
                @endif
                
                <select name="trang_thai">
                    <option value="">Tất cả trạng thái</option>
                    <option value="chua_thu" {{ $trangThai == 'chua_thu' ? 'selected' : '' }}>Chưa thu</option>
                    <option value="da_thu" {{ $trangThai == 'da_thu' ? 'selected' : '' }}>Đã thu</option>
                </select>

                <div class="filter-buttons">
                    <button type="submit" class="btn-search">Lọc & Tìm kiếm</button>
                    <a href="{{ route('debt-collections.index') }}" class="btn-reset">Đặt lại</a>
                </div>
            </form>
        </div>

        <div class="actions-bar">
            <a href="{{ route('debt-collections.create') }}" class="btn">+ Thêm mới</a>
            <a href="{{ route('debt-collections.bao-cao-ngay') }}" class="btn">📊 Báo cáo ngày</a>
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
                <div class="debt-card-money">{{ number_format($item->so_tien, 0) }}đ</div>
                <div class="debt-card-info">
                    Tháng {{ $item->thang }}/{{ $item->nam }}
                    @if($viewMode !== 'thang_nay' && ($item->nam < $nam || ($item->nam == $nam && $item->thang < $thang)))
                        <span class="month-badge old-debt">Nợ cũ</span>
                    @endif
                </div>
                @if($item->trang_thai == 'da_thu')
                    <div class="debt-card-status status-completed">✓ Thu lúc: {{ $item->ngay_thu_thuc_te->format('H:i - d/m/Y') }}</div>
                @else
                    <div class="debt-card-status status-pending">⏳ Chưa thu</div>
                @endif
                <div class="debt-card-actions">
                    @if($item->trang_thai == 'chua_thu')
                    <form method="POST" action="{{ route('debt-collections.thu', $item) }}" class="form-thu">
                        @csrf
                        <button type="submit" class="btn-thu">Đã thu tiền</button>
                    </form>
                    @else
                    <button class="btn-thu" disabled>Đã thu rồi</button>
                    @endif
                    <form method="POST" action="{{ route('debt-collections.destroy', $item) }}" class="form-delete" onsubmit="return confirm('Xóa?')">
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

        // Fix cho mobile - đảm bảo form luôn dùng POST
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('.form-thu, .form-delete');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const url = this.action;
                    
                    // Lấy CSRF token
                    const csrfToken = this.querySelector('input[name="_token"]').value;
                    
                    fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (response.ok || response.redirected) {
                            window.location.reload();
                        } else {
                            return response.text().then(text => {
                                console.error('Error response:', text);
                                alert('Có lỗi xảy ra: ' + response.status);
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        alert('Lỗi kết nối: ' + error.message);
                    });
                });
            });
        });
    </script>

    <div style="text-align: center; padding: 20px; color: #999; font-size: 12px;">
        COPYRIGHT BY HAILAM
    </div>
</body>
</html>
