<?php

namespace App\Contracts\Models;

interface OrderItemOptionContract
{
    public const TABLE = 'order_item_option';
    public const ID = 'id';
    public const ORDER_ITEM = 'order_item_id';
    public const OPTION = 'option_id';
}
