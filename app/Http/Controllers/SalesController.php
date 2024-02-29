<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateSalesRequest;
use App\Http\Resources\SalesResource;
use App\Models\Sales;
use App\Models\Products;

class SalesController extends Controller
{
    private $model;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Sales $sales)
    {
        $this->model = $sales;
    }

    /**
     * @OA\Post(
     * path="/sales",
     * operationId="new_sales",
     * tags={"Store Sales"},
     * summary="Register a new sales",
     * description="Register a new sales",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"tb_client_id", "tb_product_id","price","quantity"},
     *               @OA\Property(property="tb_client_id", type="integer"),
     *               @OA\Property(property="tb_product_id", type="integer"),
     *               @OA\Property(property="price", type="decimal"),
     *               @OA\Property(property="quantity", type="integer"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Register Successfully",
     *          @OA\JsonContent(
     *              example={           
     *                    "tb_client_id": 1,
     *                    "tb_product_id": 2,
     *                    "price": 8.50,
     *                    "quantity": 10                            
     *              }
     *          )
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *       ),
     * )
     */
    public function store(StoreUpdateSalesRequest $request)
    {
        try {
            $data = $request->validated();
            $sale = Sales::create($data);
            return new SalesResource($sale);
        } catch (\Exception $e) {
            return [
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * @OA\get(
     *     path="/sales",
     *      operationId="display_sales",
     *     tags={"Show Sales"},
     *     summary="Display all sales",
     *     description="Display all sales",
     *     @OA\Response(response="200", description="sales show successfully"),
     *     @OA\Response(response="400", description="Invalid request")
     * )
     */
    public function index()
    {
        try {
            $sales = $this->model::paginate();
            if (count($sales) <= 0) {
                return [
                    'message' => 'Nenhuma venda foi encontrado!',
                ];
            }
            return SalesResource::collection($sales);
        } catch (\Exception $e) {
            return [
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * @OA\Get(
     * path="/sales/{id}",
     * operationId="sale_show",
     * tags={"Show Sale"},
     * summary="Show one sale from id",
     * description="Show one sale from id",
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Sale id",
     *         required=true,
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function showSale($sale_id)
    {
        try {
            $sales = $this->model::find($sale_id);
            if (!$sales) {
                return [
                    'message' => 'Nenhuma venda encontrada com o id ' . $sale_id . ' informado!',
                ];
            }
            return $this->sales_structure($sales);
        } catch (\Exception $e) {
            return [
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Assemble the order structure
     * @param Sales $sales
     * @return array
     */
    private function sales_structure($sales)
    {
        $products = new Products();
        $result = [];
        foreach ($sales->product_id as $product_id) {
            $products = $products->find($product_id);
            $result[] = [
                'name' => $products->name,
                'price' => $products->price
            ];
        }

        return
            [
                'sales_id' => $sales->id,
                'product' => $result
            ];
    }
}
