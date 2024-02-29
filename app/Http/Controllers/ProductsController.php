<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateProductsRequest;
use App\Http\Resources\ProductsResource;
use App\Models\Products;

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
     * @OA\Post(
     * path="/products",
     * operationId="new_products",
     * tags={"Store Products"},
     * summary="Register a new products",
     * description="Register a new products",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"name", "price","description"},
     *               @OA\Property(property="name", type="text"),
     *               @OA\Property(property="description", type="text"),
     *               @OA\Property(property="price", type="decimal"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Register Successfully",
     *          @OA\JsonContent(
     *              example={           
     *                    "name": "Celular 1",
     *                    "price": 2.300,
     *                    "description": "Lorem ipsum dolor sit amet",                          
     *              }
     *          )
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *       ),
     * )
     */
    public function store(StoreUpdateProductsRequest $request)
    {
        try {
            $data = $request->validated();
            $products = Products::create($data);
            return new ProductsResource($products);
        } catch (\Exception $e) {
            return [
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * @OA\get(
     *     path="/products",
     *      operationId="display_products",
     *     tags={"Show Products"},
     *     summary="Display all products",
     *     description="Display all products",
     *     @OA\Response(response="200", description="products show successfully"),
     *     @OA\Response(response="400", description="Invalid request")
     * )
     */
    public function index()
    {
        try {
            $products = $this->model::paginate();
            if (count($products) <= 0) {
                return [
                    'message' => 'Nenhum produto foi encontrado!',
                ];
            }
            return ProductsResource::collection($products);
        } catch (\Exception $e) {
            return [
                'message' => $e->getMessage()
            ];
        }
    }
}
