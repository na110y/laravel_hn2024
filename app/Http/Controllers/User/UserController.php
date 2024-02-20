<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\infoUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function getListUser(Request $request)
    {
        try {
            $info_user = infoUser::query()
            ->select([
                'info_user.*'
            ])
            ->orderBy('created_at', 'desc')->get();
            return $info_user;
        } catch (\Throwable $th) {
            Log::error('Error at ' . $th->getFile() . ' : ' . __METHOD__ . $th->getLine() . ' : ' . $th->getMessage());
            return response([
                'status' => 500,
                'data' => []
            ], 500);
        }
    }
}
