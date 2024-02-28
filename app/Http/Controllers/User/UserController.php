<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\User\infoUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class UserController extends Controller
{
    // lấy danh sách thông tin của người dùng khi đăng nhập thành công
    public function getInfoUser(Request $request) 
    {
        try {
            $user = $request->session()->get('user');
            $info_user = infoUser::where('user_id', $user->user_id)
            ->select([
                'info_user.*'
            ])
            ->first();
            return response()->json(['info_user' => $info_user]);
        } catch (\Throwable $th) {
            Log::error('Error at ' . $th->getFile() . ' : ' . __METHOD__ . $th->getLine() . ' : ' . $th->getMessage());
            return response([
                'status' => 500,
                'data' => []
            ], 500);
        } 
    }

    // sửa thông tin của người dùng
    public function updateInfo(Request $request) 
    {
        try {
            $info_user = $request->session()->get('user');
            $checkID = $info_user->user_id;
            $user_info = infoUser::where('user_id', $checkID)->update([
                'fullName' => $request->fullName,
                'adress' => $request->adress,
                'sdt' => $request->sdt,
                'gender' => $request->gender,
                'dateOfBirst' => $request->dateOfBirst,
                'updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                'staff' => $info_user->name,
            ]);

            if (!$user_info) {
                return response()->json(['error' => 'Thất bại'], 500);
            }
            return response()->json(['message' => 'Thành công'], 200);
        } catch (\Throwable $th) {
            Log::error('Error at ' . $th->getFile() . ' : ' . __METHOD__ . $th->getLine() . ' : ' . $th->getMessage());
            return response([
                'status' => 500,
                'data' => []
            ], 500);
        }
    }

    // lấy ra thông tin
    public function getListUser(Request $request)
    {
        try {
            $info_user = infoUser::query()
            ->select([
                'info_user.*'
            ])
            ->get();
            return response()->json(['info_user' => $info_user[0]]);
        } catch (\Throwable $th) {
            Log::error('Error at ' . $th->getFile() . ' : ' . __METHOD__ . $th->getLine() . ' : ' . $th->getMessage());
            return response([
                'status' => 500,
                'data' => []
            ], 500);
        }
    }
}
