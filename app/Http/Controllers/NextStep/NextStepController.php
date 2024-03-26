<?php

namespace App\Http\Controllers\NextStep;

use App\Http\Controllers\Controller;
use App\Models\UserConfirmProduct\NextStepPending;
use App\Models\UserConfirmProduct\UserConfirmProductCart;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NextStepController extends Controller
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
    // List product user
    public function getListProduct(Request $request) 
    {
        try {
            $data = UserConfirmProductCart::query()
            ->select([
                'product_pending.*',
                DB::raw('(CASE 
                    WHEN product_pending.step = 0 THEN 0
                    WHEN product_pending.step = 1 THEN 1
                    WHEN product_pending.step = 2 THEN 2
                    WHEN product_pending.step = 3 THEN 3
                    WHEN product_pending.step = 4 THEN 4
                    ELSE "unknown"
                END) AS classStep'),
            ])
            ->get();

            return $data;
        } catch (\Throwable $th) {
            Log::error('Error at ' . $th->getFile() . ' : ' . __METHOD__ . $th->getLine() . ' : ' . $th->getMessage());
            return response([
                'status' => 500,
                'data' => []
            ], 500);
        } 
    }

    // Next step product pending
    public function nextStepProductPending(Request $request) 
    {
        try {
            DB::beginTransaction();

            $data = UserConfirmProductCart::where('id', $request->id)->select(['product_pending.step'])->first();
            $currentStep = $data->step;
            $list_step = self::LIST_STEP;
            if ($currentStep < count($list_step) - 1) {
                $nextStep = self::LIST_STEP[$currentStep + 1]['step'];
            } else {
                throw new \Exception("Không thể chuyển sang bước tiếp theo.");
            }

            $update_step = UserConfirmProductCart::where('id', $request->id)
            ->update([
                'step' => $nextStep,
                'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
            ]);

            Log::info($nextStep);

            if (!$update_step) {
                DB::rollBack();
                return response()->json(['error' => 'Thất bại'], 500);
            }

            DB::commit();
            return response()->json(['Thành công!'], 200);
        } catch (\Throwable $th) {
            Log::error('Error at ' . $th->getFile() . ' : ' . __METHOD__ . $th->getLine() . ' : ' . $th->getMessage());
            return response([
                'status' => 500,
                'data' => []
            ], 500);
        } 
    }
}
