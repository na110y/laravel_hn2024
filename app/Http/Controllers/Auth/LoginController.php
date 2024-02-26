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

class LoginController extends Controller
{
    use HasApiTokens, Notifiable;

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $user_name = $user->name;
                session(['user_name' => $user_name]);
                return response()->json(['message' => 'Đăng nhập thành công', 'user_name' => $user_name], 200);
            } else {
                throw new AuthenticationException('Thông tin đăng nhập không hợp lệ');
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Đã xảy ra lỗi trong quá trình đăng nhập'], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            if ($user) {
                if ($user->tokens) {
                    $user->tokens->each(function ($token, $key) {
                        $token->delete();
                    });
                }
                return response()->json(['message' => 'Đăng xuất thành công!'], 200);
            } else {
                return response()->json(['error' => 'Thất bại'], 500);
            }
            return response()->json(['message' => 'Đăng xuất thành công!'], 200);
        } catch (\Exception $th) {
            Log::error('Error at ' . $th->getFile() . ' : ' . __METHOD__ . $th->getLine() . ' : ' . $th->getMessage());
            throw new \Exception('Error at ' . $th->getFile() . ' : ' . __METHOD__ . $th->getLine() . ' : ' . $th->getMessage());
        }
    }
}
