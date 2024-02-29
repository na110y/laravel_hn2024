<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;
    protected $table = 'product_ao';
    protected $fillable = [
        "user_id",
        "product_code",
        "img",
        "name",
        "fee",
        "sale",
    ];
    public $timestamps = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
