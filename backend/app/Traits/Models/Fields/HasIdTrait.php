<?php

namespace App\Traits\Models\Fields;

use App\Contracts\Models\Fields\IdContract;

trait HasIdTrait
{
    public function getId(): int
    {
        return $this->{IdContract::ID};
    }
}
