<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Orders\List;

class OrderController extends Controller
{
    public function index(Request $request): List\PaginatorResource
    {
        return new List\PaginatorResource($request->user()->orders);
    }
}
