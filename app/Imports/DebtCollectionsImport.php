<?php

namespace App\Imports;

use App\Models\DebtCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DebtCollectionsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new DebtCollection([
            'ho_ten' => $row['ho_ten'] ?? $row['họ_ten'],
            'so_quay' => $row['so_quay'] ?? $row['số_quầy'],
            'so_tien' => $row['so_tien'] ?? $row['số_tiền'],
            'ngay_thu_du_kien' => $row['ngay_thu_du_kien'] ?? $row['ngày_thu_dự_kiến'] ?? now(),
            'thang' => $row['thang'] ?? $row['tháng'],
            'nam' => $row['nam'] ?? $row['năm'],
            'trang_thai' => 'chua_thu',
            'ngay_thu_thuc_te' => null
        ]);
    }

    public function rules(): array
    {
        return [
            '*.ho_ten' => 'required|string',
            '*.so_quay' => 'required|string',
            '*.so_tien' => 'required|numeric|min:0',
            '*.ngay_thu_du_kien' => 'required|date',
            '*.thang' => 'required|integer|min:1|max:12',
            '*.nam' => 'required|integer|min:2020'
        ];
    }
}
