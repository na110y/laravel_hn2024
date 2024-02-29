<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\branchProduct;
use App\Models\Product\product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function getProduct()
    {
        try {
            $product = product::query()
            ->select([
                'product_ao.*'
            ])
            ->get();
            return $product;
        } catch (\Throwable $th) {
            Log::error('Error at ' . $th->getFile() . ' : ' . __METHOD__ . $th->getLine() . ' : ' . $th->getMessage());
            return response([
                'status' => 500,
                'data' => []
            ], 500);
        }
    }

    // chi tiết sản phẩm
    public function getDetailProduct(Request $request)
    {
        try {
            $detailProduct = Product::where('product_code', $request->product_code)
            ->leftJoin(
                'branch_product',
                'branch_product.productCode',
                '=',
                'product_ao.product_code'
            )
            ->select([
                'product_ao.img',
                'product_ao.name as product_name'.
                'product_ao.fee',
                'product_ao.sale',
                'branch_product.*'
            ])->first();

            $detailProductBranch = branchProduct::where('productCode', $request->product_code )
            ->select([
                'branch_product.*',
            ])->get();

            if (!$detailProductBranch) {
                Log::error("ProductController - detailProductBranch: " . "Thất bại!");
                return 0;
            }

            return response(['detailProduct'=>$detailProduct, 'detailProductBranch' => $detailProductBranch],200);

        }catch (\Throwable $th) {
            error_log($th);
            Log::error('-------getDetailProduct-------');
            Log::error('Error at ' . $th->getFile() . ' : ' . __METHOD__ . $th->getLine() . ' : ' . $th->getMessage());
            return response(['error' => $th], 500);
        }
    }
}
