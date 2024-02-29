<?php

namespace App\Models\UserConfirmProduct;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserConfirmProductCart extends Model
{
    use HasFactory;
    protected $table = 'user_confirms_product';
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
        "payment",
        "staff",
    ];
    public $timestamps = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
