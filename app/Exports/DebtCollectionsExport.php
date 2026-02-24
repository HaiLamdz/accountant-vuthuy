<?php

namespace App\Exports;

use App\Models\DebtCollection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DebtCollectionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $thang;
    protected $nam;

    public function __construct($thang, $nam)
    {
        $this->thang = $thang;
        $this->nam = $nam;
    }

    public function collection()
    {
        return DebtCollection::where('thang', $this->thang)
            ->where('nam', $this->nam)
            ->orderBy('so_quay')
            ->get();
    }

    public function headings(): array
    {
        return [
            'STT',
            'Họ tên',
            'Số quầy',
            'Số tiền',
            'Ngày thu dự kiến',
            'Tháng',
            'Năm',
            'Trạng thái',
            'Ngày thu thực tế'
        ];
    }

    public function map($debtCollection): array
    {
        static $stt = 0;
        $stt++;

        return [
            $stt,
            $debtCollection->ho_ten,
            $debtCollection->so_quay,
            $debtCollection->so_tien,
            $debtCollection->ngay_thu_du_kien->format('d/m/Y'),
            $debtCollection->thang,
            $debtCollection->nam,
            $debtCollection->trang_thai == 'da_thu' ? 'Đã thu' : 'Chưa thu',
            $debtCollection->ngay_thu_thuc_te ? $debtCollection->ngay_thu_thuc_te->format('d/m/Y') : ''
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
