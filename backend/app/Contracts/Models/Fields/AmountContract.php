<?php

namespace App\Contracts\Models\Fields;

use App\Contracts\Models\FieldNames\AmountFieldNameContract;
use App\Interfaces\AmountInterface;

interface AmountContract extends AmountFieldNameContract, AmountInterface {}
