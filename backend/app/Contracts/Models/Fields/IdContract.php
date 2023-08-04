<?php

namespace App\Contracts\Models\Fields;

use App\Contracts\Models\FieldNames\IdFieldNameContract;
use App\Interfaces\IdInterface;

interface IdContract extends IdFieldNameContract, IdInterface
{
}
