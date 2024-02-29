<?php

namespace App\Models\UserCart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCart extends Model
{
    use HasFactory;
    protected $table = 'user_cart';
    protected $fillable = [
        "user_id",
        "product_code",
        "product_name",
        "product_price",
        "img",
        "note",
        "size",
        "created_at",
        "updated_at",
        "staff",
    ];
    public $timestamps = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
