<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class infoUser extends Model
{
    use HasFactory;
    protected $table = 'info_user';
    protected $fillable = [
        "id",
        "fullName",
        "age",
        "adress",
        "created_at",
        "update_at",
        "staff",
    ];
    public $timestamps = true;

    public function __construct(array $attributes = [])
    {
        $this->connection = session('campus_db');
        parent::__construct($attributes);
    }
}
