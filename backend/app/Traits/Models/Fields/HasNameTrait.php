<?php

namespace App\Traits\Models\Fields;

use App\Contracts\Models\Fields\NameContract;

trait HasNameTrait
{
    public function getName(): string
    {
        return $this->{NameContract::NAME};
    }

    public function setName(string $name): void
    {
        $this->{NameContract::NAME} = $name;
    }
}
