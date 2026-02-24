# 📊 Hệ Thống Quản Lý Thu Nợ

Ứng dụng web quản lý thu nợ cho các quầy hàng, giúp theo dõi và quản lý các khoản thu hàng ngày.

## ✨ Tính năng

- 📅 **Quản lý theo ngày**: Xem danh sách các quầy cần thu theo ngày
- 💰 **Thu tiền nhanh**: Đánh dấu đã thu tiền chỉ với 1 click
- 📊 **Thống kê**: Xem tổng thu, số lượng đã thu/chưa thu
- 🔍 **Lọc dữ liệu**: Lọc theo ngày, tháng, năm và trạng thái
- 📤 **Import/Export Excel**: Nhập và xuất dữ liệu dễ dàng
- 📱 **Responsive**: Giao diện tối ưu cho mobile

## 🛠️ Công nghệ sử dụng

- **Backend**: Laravel 12
- **Database**: SQLite
- **Frontend**: Blade Templates, CSS thuần
- **Excel**: Maatwebsite/Excel

## 📋 Yêu cầu hệ thống

- PHP >= 8.2
- Composer
- SQLite (hoặc MySQL/PostgreSQL)

## 🚀 Cài đặt

### 1. Clone dự án

```bash
git clone <repository-url>
cd accountant_hailam
```

### 2. Cài đặt dependencies

```bash
composer install
```

### 3. Cấu hình môi trường

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Tạo database

```bash
php artisan migrate
```

### 5. Tạo dữ liệu mẫu (tùy chọn)

```bash
php artisan db:seed --class=DebtCollectionSeeder
```

### 6. Chạy ứng dụng

```bash
php artisan serve
```

Truy cập: http://localhost:8000

## 📱 Truy cập từ điện thoại/máy tính khác

### Sử dụng ngrok (Khuyến nghị)

1. Tải ngrok: https://ngrok.com/download
2. Chạy Laravel server:
```bash
php artisan serve
```
3. Mở terminal mới và chạy:
```bash
ngrok http 8000
```
4. Copy link `https://xxx.ngrok.io` và truy cập từ bất kỳ đâu

### Sử dụng Cloudflare Tunnel (Miễn phí)

1. Tải cloudflared: https://github.com/cloudflare/cloudflared/releases
2. Chạy:
```bash
cloudflared tunnel --url http://localhost:8000
```

## 📖 Hướng dẫn sử dụng

### Thêm khoản thu nợ mới

1. Click nút **"+ Thêm mới"**
2. Điền thông tin:
   - Họ tên
   - Số quầy
   - Số tiền
   - Ngày thu dự kiến
   - Tháng/Năm
3. Click **"Lưu"**

### Thu tiền

1. Tìm quầy cần thu trong danh sách
2. Click nút **"Đã thu tiền"**
3. Hệ thống tự động lưu ngày thu thực tế

### Lọc dữ liệu

- **Chọn ngày**: Xem danh sách thu theo ngày cụ thể
- **Chọn trạng thái**: Lọc "Tất cả", "Chưa thu" hoặc "Đã thu"
- **Xem thống kê**: Click nút "Thống kê" để xem theo tháng/năm khác

### Import dữ liệu từ Excel

1. Click nút **"Import"**
2. Chọn file Excel (.xlsx, .xls, .csv)
3. File cần có các cột:
   - `ho_ten`: Họ tên
   - `so_quay`: Số quầy (VD: Q01)
   - `so_tien`: Số tiền (VD: 500000)
   - `ngay_thu_du_kien`: Ngày thu (VD: 2026-02-18)
   - `thang`: Tháng (1-12)
   - `nam`: Năm (VD: 2026)

4. Tải file mẫu bằng cách click **"📋 Mẫu"**

### Export dữ liệu ra Excel

1. Click nút **"Export"**
2. File Excel sẽ được tải về với tên: `thu-no-thang-{thang}-{nam}.xlsx`

## 📁 Cấu trúc dự án

```
accountant_hailam/
├── app/
│   ├── Http/Controllers/
│   │   └── DebtCollectionController.php
│   ├── Models/
│   │   └── DebtCollection.php
│   ├── Exports/
│   │   └── DebtCollectionsExport.php
│   └── Imports/
│       └── DebtCollectionsImport.php
├── database/
│   ├── migrations/
│   │   └── 2024_02_18_000001_create_debt_collections_table.php
│   └── seeders/
│       └── DebtCollectionSeeder.php
├── resources/
│   └── views/
│       └── debt-collections/
│           ├── index.blade.php
│           └── create.blade.php
└── routes/
    └── web.php
```

## 🗄️ Cấu trúc Database

### Bảng `debt_collections`

| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | bigint | ID tự tăng |
| ho_ten | string | Họ tên người kinh doanh |
| so_quay | string | Số quầy |
| so_tien | decimal | Số tiền cần thu |
| thang | integer | Tháng (1-12) |
| nam | integer | Năm |
| ngay_thu_du_kien | date | Ngày dự kiến thu |
| trang_thai | enum | Trạng thái: 'chua_thu', 'da_thu' |
| ngay_thu_thuc_te | date | Ngày thực tế đã thu |
| created_at | timestamp | Ngày tạo |
| updated_at | timestamp | Ngày cập nhật |

## 🔧 Xử lý lỗi

Tất cả các lỗi đều được bắt và hiển thị thông báo thân thiện:
- ✅ Thành công: Thông báo màu xanh
- ❌ Lỗi: "Hệ thống đang gặp sự cố, vui lòng thử lại sau!"

## 🎨 Giao diện

- Thiết kế đơn giản, dễ sử dụng
- Tối ưu cho mobile
- Màu sắc nhẹ nhàng, không chói mắt
- Nút bấm có hiệu ứng shadow nổi bật

## 📝 Routes

| Method | URL | Chức năng |
|--------|-----|-----------|
| GET | `/` | Trang chủ - Danh sách thu nợ |
| GET | `/them-moi` | Form thêm mới |
| POST | `/them-moi` | Lưu khoản thu mới |
| POST | `/thu/{id}` | Đánh dấu đã thu |
| POST | `/xoa/{id}` | Xóa khoản thu |
| GET | `/export` | Export Excel |
| POST | `/import` | Import Excel |
| GET | `/template` | Tải file mẫu |

## 👨‍💻 Tác giả

Phát triển bởi HaiLam

---

**Lưu ý**: Đây là phiên bản demo, nên sử dụng cho mục đích cá nhân
