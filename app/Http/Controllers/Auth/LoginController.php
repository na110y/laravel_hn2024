<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\User\infoUser;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Schema\Blueprint;

class LoginController extends Controller
{
    use HasApiTokens, Notifiable;

    // người dùng đăng nhập
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $request->session()->put('user', $user);
                return response()->json(['message' => 'Thành công'], 200);
            } else {
                return response()->json(['message' => 'Thất bại'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error login: ' . $e->getMessage());
            return response()->json(['error' => 'Đã xảy ra lỗi trong quá trình đăng nhập'], 500);
        }
    }

    // đăng ký tài khoản người dùng
    public function register(Request $request)
    {
        try {
            $user = User::create([
                'user_id' => Str::uuid(),
                'name' => $request->user_name,
                'email' => $request->user_email,
                'password' => bcrypt($request->user_password),
            ]);
            $user_info = infoUser::create([
                'user_id' => (string) $user->user_id,
                'name' => $request->user_name,
                'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                'staff' => $user->name,
            ]);

            if (!$user_info) {
                return response()->json(['error' => 'Tạo thông tin người dùng thất bại'], 500);
            }
            
            if (!$user) {
                return response()->json(['error' => 'Đăng ký thất bại'], 500);
            }

            Auth::login($user);
            return response()->json(['message' => 'Đăng ký thành công'], 200);
        } catch (\Exception $th) {
            Log::error('Error at ' . $th->getFile() . ' : ' . __METHOD__ . $th->getLine() . ' : ' . $th->getMessage());
            throw new \Exception('Error at ' . $th->getFile() . ' : ' . __METHOD__ . $th->getLine() . ' : ' . $th->getMessage());
        }
    }

    // người dùng đăng xuất
    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return response()->json(['message' => 'Đăng xuất thành công!'], 200);
        } catch (\Exception $th) {
            Log::error('Error at ' . $th->getFile() . ' : ' . __METHOD__ . $th->getLine() . ' : ' . $th->getMessage());
            throw new \Exception('Error at ' . $th->getFile() . ' : ' . __METHOD__ . $th->getLine() . ' : ' . $th->getMessage());
        }
    }
}
