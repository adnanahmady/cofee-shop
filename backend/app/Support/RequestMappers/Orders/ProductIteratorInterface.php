<?php

namespace App\Support\RequestMappers\Orders;

interface ProductIteratorInterface extends \Iterator
{
    public function current(): ProductMapperInterface;
}
