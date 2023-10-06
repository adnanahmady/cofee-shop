<?php

namespace App\Http\Controllers\Api\V1;

use App\Api\Exchanges;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Currencies\List;

class CurrencyController extends Controller
{
    public function index(): List\PaginatorResource
    {
        return new List\PaginatorResource(
            (new Exchanges())->getIndexedRates()
        );
    }
}
