<?php

namespace App\Http\Controllers\Register;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        try {
            Log::alert($request);
            $user = User::create([
                'name' => $request->user_name,
                'email' => $request->user_email,
                // 'password' => bcrypt($request->user_password),
                'password' => Hash::make($request->password),
            ]);
            
            if (!$user) {
                return response()->json(['error' => 'Đăng ký thất bại'], 500);
            }
            return response()->json(['message' => 'Đăng ký thành công'], 200);
        } catch (\Exception $th) {
            Log::error('Error at ' . $th->getFile() . ' : ' . __METHOD__ . $th->getLine() . ' : ' . $th->getMessage());
            throw new \Exception('Error at ' . $th->getFile() . ' : ' . __METHOD__ . $th->getLine() . ' : ' . $th->getMessage());
        }
    }
}
