<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class branchProduct extends Model
{
    use HasFactory;
    protected $table = 'branch_product';
    protected $fillabale = [
        'id',
        'productCode',
        'productName',
        'productPrice',
        'Note',
        'Img',
        'Fee'

    ];
    public $timestamps = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
