<?php

namespace App\Http\Controllers\ProductPending;

use App\Exports\ExportProductExcel;
use App\Exports\ExportProductPDF;
use App\Http\Controllers\Controller;
use App\Models\Product\ProductLogs;
use App\Models\UserConfirmProduct\NextStepPending;
use App\Models\UserConfirmProduct\UserConfirmProductCart;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use niklasravnsborg\LaravelPdf\Facades\Pdf as PDF2;

class ProductPendingController extends Controller
{
    const LIST_STEP = [
        [
            "id" => 0,
            "name" => "Chờ xử lý",
            "step" => 0,
        ],
        [
            "id" => 1,
            "name" => "Chờ giao hàng",
            "step" => 1,
        ],
        [
            "id" => 2,
            "name" => "Hoàn thành",
            "step" => 2,
        ],
        [
            "id" => 3,
            "name" => "Đã hủy",
            "step" => 3,
        ],
        [
            "id" => 4,
            "name" => "Trả hàng && hoàn tiền",
            "step" => 4,
        ]
    ];

    // danh sách sản phẩm đang trong quá trình xử lý
    public function getProductPending(Request $request)
    {
        try {
            $info_user = $request->session()->get('user');
            $checkID = $info_user->user_id;

            if($checkID !== null) {
                $productCart = UserConfirmProductCart::query()
                ->where('user_id', $checkID)
                ->select([
                    'product_pending.*'
                ])
                ->orderBy('product_pending.id', 'DESC')->get();
    
                return $productCart;
            }else {
                return response()->json(['Không có gì!'], 500);
            }
        } catch (\Throwable $th) {
            Log::error('Error at ' . $th->getFile() . ' : ' . __METHOD__ . $th->getLine() . ' : ' . $th->getMessage());
            return response([
                'status' => 500,
                'data' => []
            ], 500);
        }
    }

    /**
     * tải xuống danh sách sản phẩm đang trong thời gian xử lý bằng file excel
     *
     * @param  mixed $request
     * @return mixed
     */
    public function exportExcel(Request $request)
    {
        try {
            $data = $this->getProductPending($request)->toArray();
            $export = new ExportProductExcel($data);
            return Excel::download($export, 'Danh-sach-cho-xu-ly.xlsx');
        } catch (\Throwable $th) {
            Log::error("exportExcel: " . $th->getLine() . ":" . $th->getMessage());
            throw new \Exception($th->getMessage());
            return 0;
        }
    }

    /**
     * tải xuống danh sách sản phẩm đang trong thời gian xử lý bằng PDF
     *
     * @param  mixed $request
     */
    public function exportPdf(Request $request)
    {
        try {
            $info_user = $request->session()->get('user');
            $checkID = $info_user->user_id;
            $productCart = UserConfirmProductCart::query()
                ->where('user_id', $checkID)
                ->select([
                    'product_pending.*'
                ])
                ->get();

            $pdf = new PDF2(); 
            foreach ($productCart as $product) {
                $pdf = $this->PDFNormalConfirmForm($request, $pdf, $product);
            }
            $pdf->download('Danh-sach-cho-xu-ly.pdf');
            return $pdf;
        } catch (\Throwable $th) {
            Log::error("exportExcel: " . $th->getLine() . ":" . $th->getMessage());
            throw new \Exception($th->getMessage());
            return 0;
        }
    }

    public function PDFNormalConfirmForm(Request $request, $pdf, $product)
    {
        return $pdf = $pdf::loadView('pdf.product_code', [
            'product_code' => $product->product_code,
            'product_name' => $product->product_name,
            'size' => $product->size,
            'note' => $product->note,
            'product_price' => $product->product_price,
            'staff' => $request->staff,
            'created_at' => $product->created_at,
        ], [], []);
    }

    // hủy đơn hàng 
    public function handleCancelProduct(Request $request){
        try{
            $info_user = $request->session()->get('user');
            $checkID = $info_user->user_id;

            if($checkID !== null) {
                $update = UserConfirmProductCart::where('id', $request->id)
                ->where('user_id', $checkID)
                ->update([
                    'step' => 3,
                    'status' => 3
                ]);
                if(!$update) {
                    return response()->json(['Thất bại!'], 500);
                }
                return response()->json(['Thành công!'], 200);
            }else {
                return response()->json(['Không có gì!'], 500);
            }
            
        } catch (\Throwable $th) {
            Log::error("handleCancelProduct: " . $th->getLine() . ":" . $th->getMessage());
            throw new \Exception($th->getMessage());
            return 0;
        }
    }

}
