<?php

namespace App\Imports;

use App\Models\DebtCollection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class DebtCollectionsMonthlyImport implements ToCollection
{
    protected $nam;

    public function __construct($nam = null)
    {
        $this->nam = $nam ?? now()->year;
    }

    public function collection(Collection $rows)
    {
        $imported = 0;
        $skipped = 0;

        foreach ($rows as $index => $row) {
            // Bỏ qua 3 dòng đầu: tiêu đề, trống, header cột
            if ($index <= 2) continue;

            $hoTen = $row[1] ?? null;
            $soQuay = $row[2] ?? null;

            // Bỏ dòng trống
            if (empty($hoTen) || empty($soQuay)) {
                $skipped++;
                continue;
            }

            // Chạy từ tháng 1 → tháng 12 (cột index: 4 → 15)
            for ($thang = 1; $thang <= 12; $thang++) {
                $colIndex = 3 + $thang; // tháng 1 = cột 4 (vì có thêm cột "Số tiền phải nộp 1 tháng" ở cột 3)
                $soTien = $row[$colIndex] ?? null;

                // Làm sạch số tiền
                if ($soTien) {
                    // Kiểm tra nếu là công thức Excel (bắt đầu bằng =)
                    if (is_string($soTien) && strpos($soTien, '=') === 0) {
                        $formula = substr($soTien, 1);
                        
                        // Tính toán công thức đơn giản (chỉ hỗ trợ +, -, *, /)
                        try {
                            if (preg_match('/^[\d\s\+\-\*\/\(\)\.]+$/', $formula)) {
                                $soTien = eval("return {$formula};");
                            } else {
                                $soTien = null;
                            }
                        } catch (\Exception $e) {
                            $soTien = null;
                        }
                    } else {
                        // Làm sạch số tiền thông thường
                        $soTien = str_replace([',', ' '], '', $soTien);
                        $soTien = preg_replace('/[^0-9]/', '', $soTien);
                        $soTien = (int) $soTien;
                    }
                }

                if ($soTien && is_numeric($soTien) && $soTien > 0) {
                    $exists = DebtCollection::where('ho_ten', $hoTen)
                        ->where('so_quay', $soQuay)
                        ->where('thang', $thang)
                        ->where('nam', $this->nam)
                        ->exists();

                    if (!$exists) {
                        DebtCollection::create([
                            'ho_ten' => $hoTen,
                            'so_quay' => $soQuay,
                            'so_tien' => $soTien,
                            'thang' => $thang,
                            'nam' => $this->nam,
                            'ngay_thu_du_kien' => date("Y-m-d", strtotime("$this->nam-$thang-01")),
                            'trang_thai' => 'chua_thu',
                        ]);

                        $imported++;
                    }
                }
            }
        }
    }
}