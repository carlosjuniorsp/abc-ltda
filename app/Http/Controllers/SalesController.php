<?php

namespace App\Http\Controllers;

use App\Http\Resources\SalesResource;
use App\Models\Sales;
use Illuminate\Http\Request;

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
     *              example={{           
     *                    "tb_client_id": 1,
     *                    "tb_product_id": 2,
     *                    "price": 8.50,
     *                    "quantity": 10                            
     *              }}
     *          )
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *       ),
     * )
     */
    public function store(Request $request)
    {
        try {
            foreach ($request->all() as $data) {
                return $this->validate_sale($data);
                $sale = Sales::create($data);
            }
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
     * path="/sales/{client_id}",
     * operationId="sale_show",
     * tags={"Show Sale"},
     * summary="Displaing one sale from id the client",
     * description="Displaing one sale from id the client",
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Client id",
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
    public function showSale($tb_client_id)
    {
        try {
            $sale = Sales::select('tb_sales')
                ->select('tb_sales.price', 'tb_sales.quantity', 'tb_products.name as product_name', 'tb_client.name')
                ->join('tb_client', 'tb_client.id', '=', 'tb_sales.tb_client_id')
                ->join('tb_products', 'tb_products.id', '=', 'tb_sales.tb_product_id')
                ->where('tb_sales.tb_client_id', '=', $tb_client_id)
                ->get();

            if (count($sale) <= 0) {
                return [
                    'message' => 'Nenhuma venda encontrada para o cliente informado!',
                ];
            }
            return $this->sales_structure($sale);
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

        try {
            $result = [];
            foreach ($sales as $sale) {
                array_push($result, [
                    'client' => $sale->name,
                    'produto' => $sale->product_name,
                    'price' => $sale->price,
                    'quantity' => $sale->quantity,
                    'total' => $sale->price * $sale->quantity
                ]);
            }
            return $result;
        } catch (\Exception $e) {
            return [
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Validate and display show erros
     * @param array $data
     * @return array
     */
    private function validate_sale($data)
    {
        $msg = "";
        switch ($data) {
            case is_null($data['tb_client_id']):
                $msg = 'O campo client id é obrigatório';
                break;
            case is_null($data['tb_product_id']):
                $msg = 'O campo product id é obrigatório';
                break;
            case is_null($data['price']):
                $msg = 'O campo price é obrigatório';
                break;
        }
        return [
            'message' => $msg,
        ];
    }
}
