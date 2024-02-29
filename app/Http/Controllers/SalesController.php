<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Http\Requests\StoreSalesFormRequest;

setlocale(LC_MONETARY, 'pt_BR');


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
     *               required={"product_id", "amount","quantity"},
     *               @OA\Property(property="product_id", type="integer"),
     *               @OA\Property(property="price", type="decimal"),
     *               @OA\Property(property="amount", type="integer"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=200,
     *          description="Register Successfully",
     *          @OA\JsonContent(
     *              example={
     *                  {
     *                     "product_id": 1,
     *                     "price": 1.800,
     *                     "amount": 5                  
     *                  }
     *              }
     *          )
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function store(StoreSalesFormRequest $request)
    {
        try {
            $data = $request->all();
            $sales = $this->model->create($data);
            return response()->json($sales);
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
            $sales = $this->model->all();
            if (count($sales) <= 0) {
                return [
                    'message' => 'Nenhuma venda foi encontrado!',
                ];
            }
            return response()->json($sales);
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
            $sales = $this->model::select('tb_sales')
                ->select('tb_sales.id', 'product_id', 'tb_sales.price', 'tb_sales.amount', 'tb_sales.created_at')
                ->join('tb_products', 'tb_products.id', '=', 'tb_sales.product_id')
                ->where('tb_sales.id', '=', $sale_id)
                ->orderBy('tb_sales.id')
                ->get();

            if (count($sales) <= 0) {
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
     * Set up the sales structure
     * @param array $sales
     * @return array
     */
    private function sales_structure($sales)
    {
        try {
            foreach ($sales as $sale) {
                return [
                    'sales_id' => $sale->id,
                    'product' => [
                        'product_id' => $sale->product_id,
                        'price' => $sale->price,
                        'amount' => $sale->amount
                    ],
                    'total' => $sale->price * $sale->amount
                ];
            }
        } catch (\Exception $e) {
            return [
                'message' => $e->getMessage()
            ];
        }
    }
}
