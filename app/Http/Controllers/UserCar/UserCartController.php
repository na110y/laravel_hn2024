<?php

namespace App\Http\Controllers\UserCar;

use App\Http\Controllers\Controller;
use App\Models\UserCart\UserCart;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;



class UserCartController extends Controller
{
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
}
