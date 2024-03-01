<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLogs extends Model
{
    use HasFactory;
    protected $table = 'product_buys_logs';
    protected $fillable = [
        "product_name",
        "product_price",
        "created_at",
        "action",
        "stepName",
        "staff",
    ];
    public $timestamps = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}

