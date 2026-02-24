<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DebtCollection extends Model
{
    protected $fillable = [
        'ho_ten',
        'so_quay',
        'so_tien',
        'thang',
        'nam',
        'ngay_thu_du_kien',
        'trang_thai',
        'ngay_thu_thuc_te'
    ];

    protected $casts = [
        'so_tien' => 'decimal:2',
        'ngay_thu_du_kien' => 'date',
        'ngay_thu_thuc_te' => 'date',
        'thang' => 'integer',
        'nam' => 'integer'
    ];

    public function scopeChuaThu($query)
    {
        return $query->where('trang_thai', 'chua_thu');
    }

    public function scopeDaThu($query)
    {
        return $query->where('trang_thai', 'da_thu');
    }

    public function scopeTheoThang($query, $thang, $nam)
    {
        return $query->where('thang', $thang)->where('nam', $nam);
    }

    public function scopeTheoNgay($query, $ngay)
    {
        return $query->whereDate('ngay_thu_du_kien', $ngay);
    }

    public function scopeThuHomNay($query)
    {
        return $query->whereDate('ngay_thu_du_kien', now()->toDateString())
            ->where('trang_thai', 'chua_thu');
    }

    public function chuyenSangThangSau()
    {
        $thangMoi = $this->thang + 1;
        $namMoi = $this->nam;
        
        if ($thangMoi > 12) {
            $thangMoi = 1;
            $namMoi++;
        }

        // Chuyển ngày thu sang tháng sau, cùng ngày
        $ngayThuMoi = $this->ngay_thu_du_kien->addMonth();

        return self::create([
            'ho_ten' => $this->ho_ten,
            'so_quay' => $this->so_quay,
            'so_tien' => $this->so_tien,
            'thang' => $thangMoi,
            'nam' => $namMoi,
            'ngay_thu_du_kien' => $ngayThuMoi,
            'trang_thai' => 'chua_thu',
            'ngay_thu_thuc_te' => null
        ]);
    }
}
