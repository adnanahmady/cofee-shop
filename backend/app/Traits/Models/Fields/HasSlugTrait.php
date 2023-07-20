<?php

namespace App\Traits\Models\Fields;

use App\Contracts\Models\Fields\SlugContract;

trait HasSlugTrait
{
    public function getSlug(): string
    {
        return $this->{SlugContract::SLUG};
    }

    public function setSlug(string $slug): void
    {
        $this->{SlugContract::SLUG} = $slug;
    }
}
