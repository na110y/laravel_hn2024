<?php

namespace App\Models\UserConfirmProduct;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NextStepPending extends Model
{
    use HasFactory;
    protected $table = 'next_step_pending';
    protected $fillable = [
        "step",
        "step_name",
    ];
    public $timestamps = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
