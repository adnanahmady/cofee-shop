<?php

namespace App\Support\RequestMappers\Orders;

use App\ExceptionMessages\InvalidOffsetMessage;
use App\Exceptions\MissingOffsetException;
use App\Http\Requests\Api\V1\Orders\StoreRequest;
use App\Repositories\OptionRepository;

final class CustomizationsIterator implements \Iterator
{
    private int $index = 0;

    /**
     * Customization must be a two dimensional array.
     * e.g. [[key => value, key2, value2]].
     *
     * @var array<<string>>
     */
    private readonly array $customizations;
    private readonly OptionRepository $optionRepository;

    public function __construct(array $customizations)
    {
        $this->customizations = array_values($customizations);
        $this->optionRepository = new OptionRepository();
    }

    public function current(): CustomizationDto
    {
        return new CustomizationDto(option: $this->getOption());
    }

    private function getOption(): mixed
    {
        try {
            return $this->optionRepository->find(
                $this->customizations[$this->index][StoreRequest::OPTION_ID]
            );
        } catch (\ErrorException) {
            $message = new InvalidOffsetMessage(
                offset: StoreRequest::OPTION_ID
            );
            throw new MissingOffsetException($message);
        }
    }

    public function next(): void
    {
        ++$this->index;
    }

    public function key(): int
    {
        return $this->index;
    }

    public function valid(): bool
    {
        return key_exists(
            $this->index,
            $this->customizations
        );
    }

    public function rewind(): void
    {
        $this->index = 0;
    }
}
