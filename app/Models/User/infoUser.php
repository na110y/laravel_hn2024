<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class infoUser extends Model
{
    use HasFactory;
    protected $table = 'info_user';
    protected $fillable = [
        "user_id",
        "fullName",
        "adress",
        "sdt",
        "img",
        "free",
        "gender",
        "dateOfBirst",
        "created_at",
        "updated_at",
    ];
    public $timestamps = true;
}
