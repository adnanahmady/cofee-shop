<?php

namespace Tests\Feature\Products;

use App\Models\Customization;
use App\Models\Option;
use App\Models\Product;
use App\Models\User;
use App\Repositories\ProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Resources\Api\V1\Products\List;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use Tests\Traits\LetsBeTrait;

class ListTest extends TestCase
{
    use RefreshDatabase;
    use LetsBeTrait;

    public function test_product_customization_options_should_be_shown_as_expected(): void
    {
        $repository = new ProductRepository();
        $customization = createCustomization([Customization::NAME => 'Size']);
        array_map(fn ($option) => createOption([
            Option::NAME => $option,
            Option::CUSTOMIZATION => $customization,
        ]), $options = ['small', 'medium', 'large']);
        $repository->addCustomization(createProduct(), $customization);
        $this->login();

        $item = $this->request()->json(
            $this->join([List\PaginatorCollection::DATA, 0])
        );

        $this->assertSame([[
            List\CustomizationResource::NAME => $customization->getName(),
            List\CustomizationResource::OPTIONS => $options,
        ]], $item[List\ItemResource::CUSTOMIZATIONS]);
    }

    public function test_each_product_needs_to_be_shown_in_proper_format(): void
    {
        $priceObject = createProduct()->getPriceObject();
        $this->login();

        $item = $this->request()->json(
            $this->join([List\PaginatorCollection::DATA, 0])
        );

        $this->assertIsInt($item[List\ItemResource::PRODUCT_ID]);
        $this->assertIsString($item[List\ItemResource::NAME]);
        $this->assertIsInt($item[List\ItemResource::AMOUNT]);
        $this->assertSame(
            $item[List\ItemResource::PRICE],
            $priceObject->represent()
        );
    }

    private function join(array $path): string
    {
        return join('.', $path);
    }

    public function test_user_should_receive_existing_products_list(): void
    {
        createProduct([Product::AMOUNT => 3], $available = 8);
        createProduct(fields: [Product::AMOUNT => 0], count: 3);
        $this->login();

        $data = $this->request()->json(
            List\PaginatorCollection::DATA
        );

        $this->assertCount($available, $data);
    }

    public function test_it_should_response_with_proper_status_code(): void
    {
        $this->login();

        $response = $this->request();

        $response->assertOk();
    }

    private function login(): User
    {
        return $this->letsBe(createUser());
    }

    private function request(): TestResponse
    {
        return $this->getJson(route('api.v1.products.index'));
    }
}
