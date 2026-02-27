<?php

namespace App\Http\Controllers;

use App\Models\DebtCollection;
use App\Exports\DebtCollectionsExport;
use App\Imports\DebtCollectionsMonthlyImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DebtCollectionController extends Controller
{
    public function index(Request $request)
    {
        try {
            $thang = $request->input('thang', now()->month);
            $nam = $request->input('nam', now()->year);
            $trangThai = $request->input('trang_thai');
            $timKiem = $request->input('tim_kiem');
            $viewMode = $request->input('view', 'thang_nay'); // thang_nay, no_cu

            // Query base
            $query = DebtCollection::query();

            // Xử lý theo view mode
            if ($viewMode === 'thang_nay') {
                $query->where('thang', $thang)->where('nam', $nam);
            } elseif ($viewMode === 'no_cu') {
                // Nợ tháng trước (cả đã thu và chưa thu)
                $query->where(function($q) use ($thang, $nam) {
                    $q->where('nam', '<', $nam)
                      ->orWhere(function($q2) use ($thang, $nam) {
                          $q2->where('nam', $nam)->where('thang', '<', $thang);
                      });
                });
            }

            if ($trangThai) {
                $query->where('trang_thai', $trangThai);
            }

            // Tìm kiếm theo tên hoặc số quầy
            if ($timKiem) {
                $query->where(function($q) use ($timKiem) {
                    $q->where('ho_ten', 'like', '%' . $timKiem . '%')
                      ->orWhere('so_quay', 'like', '%' . $timKiem . '%');
                });
            }

            $danhSach = $query->orderBy('trang_thai')->orderBy('so_quay')->get();

            // Thống kê theo bộ lọc
            $tongTien = $danhSach->sum('so_tien');
            $soLuongDaThu = $danhSach->where('trang_thai', 'da_thu')->count();
            $soLuongChuaThu = $danhSach->where('trang_thai', 'chua_thu')->count();
            $tongTienDaThu = $danhSach->where('trang_thai', 'da_thu')->sum('so_tien');

            // Thống kê dashboard
            $thangNayStats = DebtCollection::where('thang', $thang)->where('nam', $nam)->get();
            $noCuCount = DebtCollection::where('trang_thai', 'chua_thu')
                ->where(function($q) use ($thang, $nam) {
                    $q->where('nam', '<', $nam)
                      ->orWhere(function($q2) use ($thang, $nam) {
                          $q2->where('nam', $nam)->where('thang', '<', $thang);
                      });
                })->count();

            return view('debt-collections.index', compact(
                'danhSach',
                'thang',
                'nam',
                'trangThai',
                'timKiem',
                'viewMode',
                'tongTien',
                'tongTienDaThu',
                'soLuongDaThu',
                'soLuongChuaThu',
                'thangNayStats',
                'noCuCount'
            ));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau!');
        }
    }

    public function baoCaoNgay(Request $request)
    {
        try {
            $ngay = $request->input('ngay', now()->toDateString());
            $thangNo = $request->input('thang_no'); // Tháng nợ (tháng cần thu)
            $namNo = $request->input('nam_no', now()->year);
            
            // Lấy tất cả các khoản đã thu trong ngày
            $query = DebtCollection::where('trang_thai', 'da_thu')
                ->whereDate('ngay_thu_thuc_te', $ngay);
            
            // Lọc theo tháng nợ nếu có
            if ($thangNo) {
                $query->where('thang', $thangNo)->where('nam', $namNo);
            }
            
            $danhSach = $query->orderBy('ngay_thu_thuc_te', 'desc')->get();
            
            $tongTien = $danhSach->sum('so_tien');
            $soLuong = $danhSach->count();
            
            return view('debt-collections.bao-cao-ngay', compact(
                'danhSach',
                'ngay',
                'thangNo',
                'namNo',
                'tongTien',
                'soLuong'
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
                'file' => 'required|mimes:xlsx,xls,csv|max:2048',
                'nam' => 'nullable|integer|min:2020|max:2030'
            ]);

            $nam = $request->input('nam', now()->year);

            $import = new DebtCollectionsMonthlyImport($nam);
            Excel::import($import, $request->file('file'));
            
            // Lấy số lượng từ log (hoặc có thể return từ import class)
            $logContent = file_get_contents(storage_path('logs/laravel.log'));
            preg_match('/Import completed: (\d+) records created, (\d+) rows skipped/', $logContent, $matches);
            
            if (isset($matches[1])) {
                $imported = $matches[1];
                $skipped = $matches[2];
                
                if ($imported > 0) {
                    return redirect()->back()
                        ->with('success', "Import thành công! Đã tạo {$imported} bản ghi, bỏ qua {$skipped} dòng.");
                } else {
                    return redirect()->back()
                        ->with('warning', "Import hoàn tất nhưng không có dữ liệu nào được tạo. Vui lòng kiểm tra lại file Excel!");
                }
            }
            
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
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Header
            $sheet->setCellValue('A1', 'STT');
            $sheet->setCellValue('B1', 'Họ kinh doanh');
            $sheet->setCellValue('C1', 'Quầy ki ốt');
            
            // Các tháng
            $months = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 
                       'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];
            $col = 'D';
            foreach ($months as $month) {
                $sheet->setCellValue($col . '1', $month);
                $col++;
            }
            $sheet->setCellValue('P1', 'Cộng');
            
            // Dữ liệu mẫu
            $data = [
                [1, 'Nguyễn Văn A', 'Q01', 500000, 500000, 500000, 500000, 500000, 500000, 500000, 500000, 500000, 500000, 500000, 500000, '=SUM(D2:O2)'],
                [2, 'Trần Thị B', 'Q02', 750000, 750000, 750000, 750000, 750000, 750000, 750000, 750000, 750000, 750000, 750000, 750000, '=SUM(D3:O3)'],
                [3, 'Lê Văn C', 'Q03', 1000000, 1000000, 1000000, 1000000, 1000000, 1000000, 1000000, 1000000, 1000000, 1000000, 1000000, 1000000, '=SUM(D4:O4)'],
            ];
            
            $row = 2;
            foreach ($data as $item) {
                $col = 'A';
                foreach ($item as $value) {
                    $sheet->setCellValue($col . $row, $value);
                    $col++;
                }
                $row++;
            }
            
            // Style header
            $sheet->getStyle('A1:P1')->getFont()->setBold(true);
            $sheet->getStyle('A1:P1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFE0E0E0');
            
            // Auto width
            foreach (range('A', 'P') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            
            $fileName = 'mau-import-thu-phi-' . now()->year . '.xlsx';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);
            
            $writer->save($temp_file);
            
            return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau!');
        }
    }
}
