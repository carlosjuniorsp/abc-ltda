<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
*  @OA\GET(
*      path="/api/test",
*      summary="Get all users",
*      description="Get all users",
*      tags={"Test"},
*      @OA\Parameter(
*         name="name",
*         in="query",
*         description="name",
*         required=false,
*      ),
*     @OA\Parameter(
*         name="email",
*         in="query",
*         description="email",
*         required=false,
*      ),
*     @OA\Parameter(
*         name="page",
*         in="query",
*         description="Page Number",
*         required=false,
*      ),
*      @OA\Response(
*          response=200,
*          description="OK",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*
*  )
*/
public function test(Request $request){
    return response()->json([
      'message' => 'Welcome to the API',
      'data' => $request
    ]);
  }
}
