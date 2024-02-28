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
     *     @OA\Response(response="201", description="Products registered successfully"),
     *     @OA\Response(response="422", description="Validation errors")
     * )
     */
    public function index()
    {
        try {
            $products = $this->model->all();
            if (count($products) <= 0) {
                return [
                    'message' => 'Nenhum produto foi encontrado!',
                    'status' => 200
                ];
            }
            return response()->json($products);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
