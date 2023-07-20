<?php

namespace App\Traits\Models\Fields;

use App\Contracts\Models\Fields\IdContract;
use App\Contracts\Models\Fields\NameContract;

trait HasIdTrait
{
    public function getId(): int
    {
        return $this->{IdContract::ID};
    }
}
