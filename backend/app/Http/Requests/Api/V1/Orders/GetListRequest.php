<?php

namespace App\Http\Requests\Api\V1\Orders;

use App\Contracts\Pagination\PageContract;
use App\Contracts\Pagination\PerPageContract;
use App\Http\Requests\Api\V1\AbstractFormRequest;
use App\Support\Values\Pagination\PerPageValue;

class GetListRequest extends AbstractFormRequest implements
    PageContract,
    PerPageContract
{
    public function rules(): array
    {
        return [
            self::PAGE => 'integer',
            self::PER_PAGE => 'integer',
        ];
    }

    public function getPage(): int
    {
        return $this->{self::PAGE} ?? 1;
    }

    public function getPerPage(): int
    {
        $perPageValue = new PerPageValue();

        return $this->{self::PER_PAGE} ?? $perPageValue->represent();
    }
}
