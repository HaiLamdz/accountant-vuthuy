<?php

namespace App\Http\Controllers;

use App\Models\DebtCollection;
use App\Exports\DebtCollectionsExport;
use App\Imports\DebtCollectionsImport;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class DebtCollectionController extends Controller
{
    public function index(Request $request)
    {
        try {
            $thang = $request->input('thang', now()->month);
            $nam = $request->input('nam', now()->year);
            $trangThai = $request->input('trang_thai');
            $ngay = $request->input('ngay', now()->format('Y-m-d'));

            // Danh sách theo bộ lọc
            $query = DebtCollection::whereDate('ngay_thu_du_kien', $ngay)
                ->where('thang', $thang)
                ->where('nam', $nam);

            if ($trangThai) {
                $query->where('trang_thai', $trangThai);
            }

            $danhSach = $query->orderBy('so_quay')->get();

            // Thống kê theo bộ lọc
            $queryStats = DebtCollection::whereDate('ngay_thu_du_kien', $ngay)
                ->where('thang', $thang)
                ->where('nam', $nam);

            $tongThuNgay = (clone $queryStats)->where('trang_thai', 'da_thu')->sum('so_tien');
            $soLuongDaThu = (clone $queryStats)->where('trang_thai', 'da_thu')->count();
            $soLuongChuaThu = (clone $queryStats)->where('trang_thai', 'chua_thu')->count();

            return view('debt-collections.index', compact(
                'danhSach',
                'thang',
                'nam',
                'ngay',
                'trangThai',
                'tongThuNgay',
                'soLuongDaThu',
                'soLuongChuaThu'
            ));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau!');
        }
    }

    public function create()
    {
        try {
            return view('debt-collections.create');
        } catch (\Exception $e) {
            return redirect()->route('debt-collections.index')
                ->with('error', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau!');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'ho_ten' => 'required|string|max:255',
                'so_quay' => 'required|string|max:50',
                'so_tien' => 'required|numeric|min:0',
                'ngay_thu_du_kien' => 'required|date',
                'thang' => 'required|integer|min:1|max:12',
                'nam' => 'required|integer|min:2020'
            ]);

            DebtCollection::create($validated);

            return redirect()->route('debt-collections.index')
                ->with('success', 'Thêm khoản thu nợ thành công!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau!');
        }
    }

    public function thu(DebtCollection $debtCollection)
    {
        try {
            $debtCollection->update([
                'trang_thai' => 'da_thu',
                'ngay_thu_thuc_te' => now()
            ]);

            return redirect()->back()
                ->with('success', 'Đã thu tiền từ ' . $debtCollection->ho_ten);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau!');
        }
    }

    public function chuyenThang(DebtCollection $debtCollection)
    {
        try {
            $debtCollection->chuyenSangThangSau();
            
            return redirect()->back()
                ->with('success', 'Đã chuyển sang tháng sau cho ' . $debtCollection->ho_ten);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau!');
        }
    }

    public function destroy(DebtCollection $debtCollection)
    {
        try {
            $debtCollection->delete();

            return redirect()->back()
                ->with('success', 'Đã xóa khoản thu nợ');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau!');
        }
    }

    public function export(Request $request)
    {
        try {
            $thang = $request->input('thang', now()->month);
            $nam = $request->input('nam', now()->year);

            return Excel::download(
                new DebtCollectionsExport($thang, $nam),
                "thu-no-thang-{$thang}-{$nam}.xlsx"
            );
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau!');
        }
    }

    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv|max:2048'
            ]);

            Excel::import(new DebtCollectionsImport, $request->file('file'));
            
            return redirect()->back()
                ->with('success', 'Import dữ liệu thành công!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->with('error', 'Vui lòng chọn file Excel hợp lệ!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau!');
        }
    }

    public function downloadTemplate()
    {
        try {
            $headers = [
                'ho_ten',
                'so_quay',
                'so_tien',
                'ngay_thu_du_kien',
                'thang',
                'nam'
            ];

            $data = [
                ['Nguyễn Văn A', 'Q01', 500000, now()->format('Y-m-d'), now()->month, now()->year],
                ['Trần Thị B', 'Q02', 750000, now()->addDay()->format('Y-m-d'), now()->month, now()->year],
            ];

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            $sheet->fromArray($headers, null, 'A1');
            $sheet->fromArray($data, null, 'A2');
            
            $sheet->getStyle('A1:F1')->getFont()->setBold(true);

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            
            $fileName = 'mau-import-thu-no.xlsx';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);
            
            $writer->save($temp_file);
            
            return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau!');
        }
    }
}
