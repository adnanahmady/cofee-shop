<?php

namespace Tests\Helpers\Types;

use App\Models\Customization;
use App\Models\Option;
use App\Models\Product;

final class CustomizedProductDto
{
    /**
     * @param array<Option> $options
     */
    public function __construct(
        private readonly Product $product,
        private readonly Customization $customization,
        private readonly array $options,
    ) {
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getCustomization(): Customization
    {
        return $this->customization;
    }

    /**
     * @return array<Option>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    public function getOption(int $index): Option
    {
        return $this->options[$index];
    }
}
