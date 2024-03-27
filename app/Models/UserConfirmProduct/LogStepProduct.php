<?php

namespace App\Models\UserConfirmProduct;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogStepProduct extends Model
{
    use HasFactory;
    protected $table = 'log_step_product';
    protected $fillable = [
        "user_id",
        "step",
        "status",
        "product_name",
        "staff",
        "created_at",
    ];
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
