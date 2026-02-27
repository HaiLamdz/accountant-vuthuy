# 🏪 Hệ Thống Quản Lý Thu Phí Chợ

Ứng dụng web quản lý thu phí dịch vụ sử dụng diện tích bán hàng tại chợ, được xây dựng bằng Laravel 11.

## ✨ Tính Năng

### � Quản  Lý Thu Phí
- Quản lý danh sách hộ kinh doanh và số quầy
- Theo dõi công nợ theo tháng/năm
- Phân loại: Tháng này / Nợ cũ
- Tìm kiếm theo tên hoặc số quầy
- Lọc theo trạng thái (Đã thu / Chưa thu)

### � Thug Tiền
- Đánh dấu đã thu tiền với timestamp
- Hiển thị thời gian thu chính xác
- Thống kê tổng tiền, đã thu, chưa thu

### 📈 Báo Cáo
- Báo cáo thu tiền theo ngày
- Lọc theo tháng nợ
- Xuất Excel danh sách thu phí

### 📥 Import/Export
- Import dữ liệu từ Excel (hỗ trợ công thức)
- Export danh sách ra Excel
- Template mẫu để import

### 🔐 Bảo Mật
- Đăng nhập session-based
- Middleware bảo vệ routes
- Xác nhận khi đăng xuất

## 🛠️ Yêu Cầu Hệ Thống

- PHP >= 8.2
- Composer
- MySQL/MariaDB
- Node.js & NPM (cho assets)

## 📦 Cài Đặt

### 1. Clone Repository
```bash
git clone <repository-url>
cd <project-folder>
```

### 2. Cài Đặt Dependencies
```bash
composer install
npm install
```

### 3. Cấu Hình Environment
```bash
cp .env.example .env
php artisan key:generate
```

Chỉnh sửa file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ten_database
DB_USERNAME=username
DB_PASSWORD=password

APP_TIMEZONE=Asia/Ho_Chi_Minh
```

### 4. Tạo Database
```bash
php artisan migrate
```

### 5. Build Assets
```bash
npm run build
```

### 6. Chạy Server
```bash
php artisan serve
```

Truy cập: `http://localhost:8000`

## 🔑 Đăng Nhập

**Thông tin mặc định:**
- Username: `admin`
- Password: `thuy6868`

> ⚠️ Nên đổi mật khẩu sau khi cài đặt

## 📋 Hướng Dẫn Sử Dụng

### Import Dữ Liệu Excel

1. Tải template mẫu từ nút "Template"
2. Điền dữ liệu theo format:
   - Cột A: STT
   - Cột B: Họ kinh doanh
   - Cột C: Quầy ki ốt
   - Cột D: Số tiền phải nộp 1 tháng
   - Cột E-P: Tháng 1 đến Tháng 12
   - Cột Q: Tổng 1 năm
3. Click "Import" và chọn file
4. Hệ thống tự động import và tạo records

**Lưu ý:**
- Hỗ trợ công thức Excel (VD: `=1530000+225000`)
- Tự động bỏ qua dòng trống
- Không import trùng lặp

### Thu Tiền

1. Tìm hộ kinh doanh cần thu
2. Click "Đã thu tiền"
3. Hệ thống tự động lưu thời gian thu

### Xem Báo Cáo

1. Click "📊 Báo cáo ngày"
2. Chọn ngày cần xem
3. Lọc theo tháng nợ (nếu cần)

## 🗂️ Cấu Trúc Dự Án

```
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php          # Xử lý đăng nhập/đăng xuất
│   │   └── DebtCollectionController.php # Quản lý thu phí
│   ├── Imports/
│   │   └── DebtCollectionsMonthlyImport.php # Import Excel
│   ├── Exports/
│   │   └── DebtCollectionsExport.php    # Export Excel
│   └── Models/
│       └── DebtCollection.php           # Model công nợ
├── resources/views/
│   ├── auth/
│   │   └── login.blade.php              # Trang đăng nhập
│   └── debt-collections/
│       ├── index.blade.php              # Trang chính
│       ├── bao-cao-ngay.blade.php       # Báo cáo ngày
│       └── create.blade.php             # Thêm mới
└── routes/
    └── web.php                          # Định nghĩa routes
```

## 🎨 Giao Diện

- Thiết kế responsive, mobile-friendly
- Màu chủ đạo: Xanh lá (#27ae60, #2ecc71)
- Font: Arial, sans-serif
- Icons: Emoji native

## 🚀 Deploy Lên Hosting

### Yêu Cầu Hosting
- PHP 8.1+
- MySQL/MariaDB
- 200MB dung lượng
- 512MB RAM
- LiteSpeed/Apache/Nginx

### Các Bước Deploy

1. Upload code lên hosting
2. Cấu hình `.env` với thông tin database
3. Chạy migrations: `php artisan migrate`
4. Cấu hình document root về `/public`
5. Đảm bảo folder `storage` có quyền ghi

### Cấu Hình .htaccess (nếu dùng Apache)

File `/public/.htaccess` đã có sẵn, đảm bảo mod_rewrite được bật.

## 🔧 Bảo Trì

### Xóa Dữ Liệu Cũ
```bash
php artisan tinker
>>> DB::table('debt_collections')->where('nam', '<', 2026)->delete();
```

### Backup Database
```bash
mysqldump -u username -p database_name > backup.sql
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 📝 License

Copyright © 2026 HAILAM. All rights reserved.

---

**Phiên bản:** 1.0.0  
**Ngày cập nhật:** 26/02/2026
**Lưu ý:** Đây là phiên bản demo, nên sử dụng cho mục đích cá nhân
