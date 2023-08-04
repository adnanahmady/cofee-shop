<?php

namespace App\Contracts\Models;

use App\Contracts\Models\FieldNames\IdFieldNameContract;

interface ProductCustomizationContract extends IdFieldNameContract
{
    public const TABLE = 'product_customization';
    public const CUSTOMIZATION = 'customization_id';
    public const PRODUCT = 'product_id';
}
