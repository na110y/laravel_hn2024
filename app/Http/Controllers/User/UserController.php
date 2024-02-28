<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\infoUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class UserController extends Controller
{
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

    public function postInfoUser(Request $request) 
    {
        try {
            // $user_info = new infoUser();
            // $user_info->user_id = Str::uuid();
            // $user_info->fullName = $request->fullName;
            // $user_info->adress = $request->adress;
            // $user_info->sdt = $request->sdt;
            // $user_info->img = $request->img;
            // $user_info->free = $request->free;
            // $user_info->gender = $request->gender;
            // $user_info->dateOfBirst = $request->dateOfBirst;
            // $user_info->created_at = now();
            // $user_info->updated_at =  now();

            // $user_info->save();

            $user_info = infoUser::create([
                'user_id' => Str::uuid(),
                'fullName' => $request->fullName,
                'adress' => $request->adress,
                'sdt' => $request->sdt,
                'img' => $request->img,
                'free' => $request->free,
                'gender' => $request->gender,
                'dateOfBirst' => $request->dateOfBirst,
                'created_at' => now(),
                'updated_at' =>  now(),
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

    // sửa thông tin của người dùng
    public function updateInfo(Request $request) 
    {
        try {

            $info_user = $request->session()->get('user');
            $checkID = $info_user->id;
            $user_info = infoUser::where('id', $checkID)->update([
                'fullName' => $request->fullName,
                'adress' => $request->adress,
                'sdt' => $request->sdt,
                'gender' => $request->gender,
                'dateOfBirst' => $request->dateOfBirst,
                'updated_at' => now(),
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
