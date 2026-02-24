<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DebtCollection;
use Carbon\Carbon;

class DebtCollectionSeeder extends Seeder
{
    public function run(): void
    {
        $thangHienTai = now()->month;
        $namHienTai = now()->year;

        $danhSach = [
            ['ho_ten' => 'Nguyễn Văn A', 'so_quay' => 'Q01', 'so_tien' => 500000],
            ['ho_ten' => 'Trần Thị B', 'so_quay' => 'Q02', 'so_tien' => 750000],
            ['ho_ten' => 'Lê Văn C', 'so_quay' => 'Q03', 'so_tien' => 1000000],
            ['ho_ten' => 'Phạm Thị D', 'so_quay' => 'Q04', 'so_tien' => 600000],
            ['ho_ten' => 'Hoàng Văn E', 'so_quay' => 'Q05', 'so_tien' => 850000],
            ['ho_ten' => 'Vũ Thị F', 'so_quay' => 'Q06', 'so_tien' => 450000],
            ['ho_ten' => 'Đặng Văn G', 'so_quay' => 'Q07', 'so_tien' => 900000],
            ['ho_ten' => 'Bùi Thị H', 'so_quay' => 'Q08', 'so_tien' => 550000],
        ];

        foreach ($danhSach as $item) {
            // Tạo dữ liệu cho tháng hiện tại
            DebtCollection::create([
                'ho_ten' => $item['ho_ten'],
                'so_quay' => $item['so_quay'],
                'so_tien' => $item['so_tien'],
                'ngay_thu_du_kien' => Carbon::now()->addDays(rand(0, 10)),
                'thang' => $thangHienTai,
                'nam' => $namHienTai,
                'trang_thai' => 'chua_thu',
                'ngay_thu_thuc_te' => null
            ]);

            // Tạo một số dữ liệu đã thu cho tháng trước
            if ($thangHienTai > 1) {
                DebtCollection::create([
                    'ho_ten' => $item['ho_ten'],
                    'so_quay' => $item['so_quay'],
                    'so_tien' => $item['so_tien'],
                    'ngay_thu_du_kien' => Carbon::now()->subMonth()->addDays(rand(1, 20)),
                    'thang' => $thangHienTai - 1,
                    'nam' => $namHienTai,
                    'trang_thai' => 'da_thu',
                    'ngay_thu_thuc_te' => Carbon::now()->subMonth()->addDays(rand(1, 20))
                ]);
            }
        }
    }
}
