<?php

use App\Models\Customization;
use App\Models\Option;
use App\Models\Product;
use App\Repositories\ProductRepository;

if (!function_exists('addCustomizationToProduct')) {
    /**
     * Creates and adds a customization to
     * the given product.
     *
     * @param Product       $product       product
     * @param string        $customization customization name
     * @param array<string> $options       option names
     *
     * @return array<Product, Customization>
     */
    function addCustomizationToProduct(
        Product $product,
        string $customization,
        array $options
    ): array {
        $repository = new ProductRepository();
        $customization = createCustomization([
            Customization::NAME => $customization,
        ]);
        array_map(fn ($option) => createOption([
            Option::NAME => $option,
            Option::CUSTOMIZATION => $customization,
        ]), $options);
        $repository->addCustomization($product, $customization);

        return [$product, $customization];
    }
}
