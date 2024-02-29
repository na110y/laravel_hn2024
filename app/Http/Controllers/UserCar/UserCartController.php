<?php

namespace App\Http\Controllers\UserCar;

use App\Http\Controllers\Controller;
use App\Models\UserCart\UserCart;
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
            $productCart = UserCart::query()
            ->select([
                'user_cart.*'
            ]);
            
            if (isset($request->product_code) && $request->product_code) {
                $productCart->where('user_cart.product_code', '=', $request->product_code);
            }

            $productCart->orderBy('user_cart.id', 'DESC')->get();
            $data = $productCart->paginate(8);

            return $data;

        } catch (\Throwable $th) {
            Log::error('Error at ' . $th->getFile() . ' : ' . __METHOD__ . $th->getLine() . ' : ' . $th->getMessage());
            return response([
                'status' => 500,
                'data' => []
            ], 500);
        }
    }

    // thêm sản phẩm của người dùng vào giỏ hàng để ship
    public function postDetailProductCart(Request $request)
    {
        try {
            $info_user = $request->session()->get('user');
            $checkID = $info_user->user_id;
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
            $deleteProductCart = UserCart::where('id',$request->id)->delete();
            if (!$deleteProductCart) {
                return response()->json(['Không tìm được sản phẩm cần xóa!'], 500);
            }
            return response()->json(['Xóa thành công!'], 200);
        }catch (\Throwable $th) {
            error_log($th);
            Log::error('-------delete SV EBook-------');
            Log::error('Error at ' . $th->getFile() . ' : ' . __METHOD__ . $th->getLine() . ' : ' . $th->getMessage());
            return response(['error' => $th], 500);
        }
    }

}
