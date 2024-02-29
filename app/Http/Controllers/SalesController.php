<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Http\Requests\StoreSalesFormRequest;


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
     * tags={"#1 - Sales"},
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
            $client = $this->model->create($data);
            return response()->json($client);
        } catch (\Exception $e) {
            return [
                'message' => $e->getMessage()
            ];
        }
    }
}
