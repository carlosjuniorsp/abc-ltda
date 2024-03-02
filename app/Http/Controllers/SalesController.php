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
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Register Successfully",
     *          @OA\JsonContent(
     *              example={
     *                  {           
     *                    "tb_client_id": 1,
     *                    "tb_product_id": 2,
     *                    "price": 8.50,
     *                    "quantity": 10                            
     *                  }
     *              }
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
                $this->validate_sale($data);
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
            $sale = Sales::select('tb_sales')
                ->select('tb_sales.price', 'tb_sales.quantity', 'tb_sales.id as sales_id', 'tb_sales.deleted_at', 'tb_products.name as product_name', 'tb_client.name')
                ->join('tb_client', 'tb_client.id', '=', 'tb_sales.tb_client_id')
                ->join('tb_products', 'tb_products.id', '=', 'tb_sales.tb_product_id')
                ->withTrashed()
                ->get();
            if (count($sale) <= 0) {
                return [
                    'message' => 'Nenhuma venda foi encontrado!',
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
     * @OA\Get(
     * path="/sales/{id}",
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
                ->select('tb_sales.price', 'tb_sales.quantity', 'tb_sales.id as sales_id', 'tb_sales.deleted_at', 'tb_products.name as product_name', 'tb_client.name')
                ->join('tb_client', 'tb_client.id', '=', 'tb_sales.tb_client_id')
                ->join('tb_products', 'tb_products.id', '=', 'tb_sales.tb_product_id')
                ->where('tb_sales.tb_client_id', '=', $tb_client_id)
                ->withTrashed()
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
     * @OA\delete(
     * path="/sales/{id}",
     * operationId="sale_delete",
     * tags={"Cancel a sale"},
     * summary="Cancel a sale",
     * description="Cancel a sale",
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
    public function destroy($id)
    {
        try {
            $sale = Sales::find($id);
            if (empty($sale)) {
                return [
                    'message' => 'Não foi possível cancelar, o pedido (' . $id . ') não existe!',
                ];
            }
            if ($sale->delete()) {
                return response()->json([
                    'message' => 'O pedido (' . $id . ') foi deletado com sucesso!',
                ]);
            }
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
                    'sale' => [
                        'id' => $sale->sales_id
                    ],
                    'client' => [
                        'name' => $sale->name
                    ],
                    'product' => [
                        'produto' => $sale->product_name,
                        'price' => $sale->price,
                        'quantity' => $sale->quantity,
                        'status' => is_null($sale->deleted_at) ? 'Pedido em andamento' : 'Pedido cancelado',
                        'total' => $sale->price * $sale->quantity
                    ]
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
            default:
                return $data;
        }
        return [
            'message' => $msg,
        ];
    }
}
