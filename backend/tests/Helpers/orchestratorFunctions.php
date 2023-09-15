<?php

use App\Models\Currency;
use App\Models\Customization;
use App\Models\Option;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Tests\Helpers\Types\CustomizedProductDto;

if (!function_exists('addCustomizationToProduct')) {
    /**
     * Creates and adds a customization to
     * the given product.
     *
     * @param Product       $product       product
     * @param string        $customization customization name
     * @param array<string> $options       option names
     */
    function addCustomizationToProduct(
        Product $product,
        string $customization,
        array $options,
        Currency $currency = null
    ): CustomizedProductDto {
        $repository = new ProductRepository();
        $customization = createCustomization([
            Customization::NAME => $customization,
        ]);
        $createdOptions = array_map(fn ($option) => createOption([
            Option::NAME => $option,
            Option::CUSTOMIZATION => $customization,
            Option::CURRENCY => $currency ?? Currency::factory(),
        ]), $options);
        $repository->addCustomization($product, $customization);

        return new CustomizedProductDto(
            product: $product,
            customization: $customization,
            options: $createdOptions
        );
    }
}
