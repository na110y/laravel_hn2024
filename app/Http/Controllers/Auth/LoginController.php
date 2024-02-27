<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    use HasApiTokens, Notifiable;

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $request->session()->put('user', $user);
                return response()->json(['message' => 'Đăng nhập thành công'], 200);
            } else {
                throw new AuthenticationException('Thông tin đăng nhập không hợp lệ');
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Đã xảy ra lỗi trong quá trình đăng nhập'], 500);
        }
    }

    public function register(Request $request)
    {
        try {
            $user = new User();
            $user->name = $request->user_name;
            $user->email = $request->user_email;
            $user->password = bcrypt($request->password);
            $user->email_verified_at = now();
            $user->remember_token = Str::uuid();
            $user->user_id = Str::uuid();

            $user->save();

            if (!$user) {
                return response()->json(['error' => 'Đăng ký thất bại'], 500);
            }
            return response()->json(['message' => 'Đăng ký thành công'], 200);
        } catch (\Exception $th) {
            Log::error('Error at ' . $th->getFile() . ' : ' . __METHOD__ . $th->getLine() . ' : ' . $th->getMessage());
            throw new \Exception('Error at ' . $th->getFile() . ' : ' . __METHOD__ . $th->getLine() . ' : ' . $th->getMessage());
        }
    }

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
