<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\User\infoUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class UserController extends Controller
{
    // lấy danh sách tất thông tin của người dùng khi đăng nhập thành công
    public function getAllInfoUser(Request $request) 
    {
        try {
            $admin = $request->session()->get('admin');
            $isAdimn = $admin->role;
            if($isAdimn == 1) {
                $info_user = infoUser::query()
                ->select([
                    'info_user.*'
                ]);

                if (isset($request->keywords) && $request->keywords) {
                    $searchTerm = '%' . $request->keywords . '%';
                    $info_user->where(function ($query) use ($searchTerm) {
                        $query->orWhere('info_user.id', 'like', $searchTerm)
                              ->orWhere('info_user.fullName', 'like', $searchTerm)
                              ->orWhere('info_user.adress', 'like', $searchTerm)
                              ->orWhere('info_user.sdt', 'like', $searchTerm)
                              ->orWhere('info_user.staff', 'like', $searchTerm);
                    });
                }
    
                $info_user->orderBy('info_user.id', 'DESC')->get();
                $data = $info_user->paginate(8);
    
                return $data;
            }
        } catch (\Throwable $th) {
            Log::error('Error at ' . $th->getFile() . ' : ' . __METHOD__ . $th->getLine() . ' : ' . $th->getMessage());
            return response([
                'status' => 500,
                'data' => []
            ], 500);
        } 
    }

    // Xóa thông tin của người dùng
    public function deleteDetailUser(Request $request)
    {
        try {
            DB::beginTransaction();
            $info_user = infoUser::where('id',$request->id)->first();

            if (!$info_user) {
                DB::rollBack();
                return response()->json(['message' => 'Không tìm thấy người dùng!'], 404);
            }

            $user_id =  $info_user->user_id;

            $deleteInfoUser = infoUser::where('user_id',$user_id)->delete();
            $deleteUser = User::where('user_id',$user_id)->delete();

            if (!$deleteInfoUser || !$deleteUser) {
                DB::rollBack();
                return response()->json(['message' => 'Không tìm thấy người dùng!'], 500);
            }

            DB::commit();
            return response()->json(['Xóa thành công!'], 200);
        }catch (\Throwable $th) {
            error_log($th);
            Log::error('-------delete detail user-------');
            Log::error('Error at ' . $th->getFile() . ' : ' . __METHOD__ . $th->getLine() . ' : ' . $th->getMessage());
            return response(['error' => $th], 500);
        }
    }


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
            DB::beginTransaction();
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

            DB::commit();
            if (!$user_info) {
                DB::rollBack();
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
