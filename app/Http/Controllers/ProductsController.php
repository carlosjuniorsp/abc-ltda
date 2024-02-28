<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientFormRequest;
use App\Models\Products;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    private $model;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Products $products)
    {
        $this->model = $products;
    }


    /**
     * @OA\get(
     *     path="/products",
     *      operationId="display_products",
     *     tags={"#1 - Products"},
     *     summary="Display all products",
     *     description="Display all products",
     *     @OA\Response(response="200", description="Products show successfully"),
     *     @OA\Response(response="400", description="Invalid request")
     *     @OA\Response(response="500", description="Internal server error")
     * )
     */
    public function index()
    {
        try {
            $products = $this->model->all();
            if (count($products) <= 0) {
                return [
                    'message' => 'Nenhum produto foi encontrado!',
                ];
            }
            return response()->json($products);
        } catch (\Exception $e) {
            return [
                'message' => $e->getMessage()
            ];
        }
    }
}
