<?php

namespace App\Http\Controllers\UserCar;

use App\Http\Controllers\Controller;
use App\Models\Product\ProductLogs;
use App\Models\User\infoUser;
use App\Models\UserCart\UserCart;
use App\Models\UserConfirmProduct\UserConfirmProductCart;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Utils\ResponseBuilder;



class UserCartController extends Controller
{
    
    // danh sách sản phẩm trong giỏ hàng
    public function userBuysProduct(Request $request)
    {
        try {
            $info_user = $request->session()->get('user');
            $checkID = $info_user->user_id;
            if ($checkID !== null) {

                $productCart = UserCart::query()
                ->where('user_id', $checkID)
                ->select([
                    'user_cart.*'
                ]);
                
                if (isset($request->product_code) && $request->product_code) {
                    $productCart->where('user_cart.product_code', '=', $request->product_code);
                }
    
                $productCart->orderBy('user_cart.id', 'DESC')->get();
                $data = $productCart->paginate(8);
    
                return $data;
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

    // thêm sản phẩm của người dùng vào giỏ hàng
    public function postDetailProductCart(Request $request)
    {
        try {
            $info_user = $request->session()->get('user');
            $checkID = $info_user->user_id;
            if($checkID !== null) {
                $user_info_cart = UserCart::where('user_id', $checkID)->updateOrCreate([
                    'user_id' => $checkID,
                    'product_code' => $request->product_code,
                    'product_name' => $request->product_name,
                    'product_price' => $request->fee,
                    'img' => $request->img,
                    'note' => $request->note,
                    'size' => $request->size,
                    'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    'updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    'staff' => $info_user->name,
                ]);

                if (!$user_info_cart) {
                    Log::error("postDetailProductCart" . "Thất bại!");
                    return 0;
                }
                return $user_info_cart;

            }else {
                return response()->json(['Bạn chưa đăng nhập tài khoản!'], 500);
            }
        } catch (\Throwable $th) {
            Log::error('Error at ' . $th->getFile() . ' : ' . __METHOD__ . $th->getLine() . ' : ' . $th->getMessage());
            return response([
                'status' => 500,
                'data' => []
            ], 500);
        }
    }

    // người dùng chốt sản phẩm và chuyển sang giao đoạn ship hàng
    public function postConfirmsProduct(Request $request)
    {
        try {
            $info_user = $request->session()->get('user');
            $checkID = $info_user->user_id;

            if ($checkID !== null) {
                $listData = [];
                $dataUserLogs = [];
                $list_Data = $request->listProduct;
                $selectedPayment = $request->selectedPayment;

                foreach ($list_Data as $item) {

                    if ($item['user_id'] == $checkID) {

                        $listData[] = [
                            'user_id' => $item['user_id'],
                            'product_code' => $item['product_code'],
                            'product_name' => $item['product_name'],
                            'product_price' => $item['product_price'],
                            'img' => $item['img'],
                            'note' => $item['note'],
                            'size' => $item['size'],
                            'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                            'updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                            'payment' => $selectedPayment,
                            'step' => 0,
                            'status' => 0,
                            'staff' => $info_user->name,
                        ];
    
                        $dataUserLogs[] = [
                            'product_name' => $item['product_name'],
                            'product_price' => $item['product_price'],
                            'action' => 'Xử lý dịch vụ',
                            'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                            'stepName' => "Chờ xử lý",
                            'staff' => $info_user->name,
                        ];
                    } 

                }

                $user_info_cart = UserConfirmProductCart::where('user_id', $checkID)->insert($listData);
                $new_log = ProductLogs::where('user_id', $checkID)->insert($dataUserLogs);

                $user_id = array_column($listData, 'user_id');
                $product_Code = array_column($listData, 'product_code');

                $delete_cart = UserCart::whereIn('user_id', $user_id)
                ->whereIn('product_code', $product_Code)
                ->delete();

                if(!$new_log) {
                    Log::error("Tạo log thất bại.");
                    return 0;
                } 

                if (!$delete_cart) {
                    Log::error("postDetailProductCart" . "Thất bại!");
                    return 0;
                }
                if (!$user_info_cart) {
                    Log::error("postDetailProductCart" . "Thất bại!");
                    return 0;
                }

                return response()->json(['Thành công!'], 200);
            }else {
                Log::error("Không có gì!");
                return 0;
            }
            
        } catch (\Throwable $th) {
            Log::error('Error at ' . $th->getFile() . ' : ' . __METHOD__ . $th->getLine() . ' : ' . $th->getMessage());
            return response([
                'status' => 500,
                'data' => []
            ], 500);
        }
    }

    // xóa sản phẩm khỏi giỏ hàng
    public function getDeleteDetailProductCart(Request $request)
    {
        try {
            $info_user = $request->session()->get('user');
            $checkID = $info_user->user_id;

            if ($checkID !== null) {
                $deleteProductCart = UserCart::where('id',$request->id)
                ->where('user_id', $checkID)->delete();
                if (!$deleteProductCart) {
                    return response()->json(['Không tìm được sản phẩm cần xóa!'], 500);
                }
                return response()->json(['Xóa thành công!'], 200);
            }else {
                Log::error("Không có gì!");
                return 0;
            }
        }catch (\Throwable $th) {
            error_log($th);
            Log::error('-------delete SV EBook-------');
            Log::error('Error at ' . $th->getFile() . ' : ' . __METHOD__ . $th->getLine() . ' : ' . $th->getMessage());
            return response(['error' => $th], 500);
        }
    }

}
