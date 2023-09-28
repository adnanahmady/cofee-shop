<?php

namespace App\Support\RequestMappers\Orders;

class ProductsIterator implements ProductIteratorInterface
{
    private int $index = 0;

    public function __construct(readonly private array $data) {}

    public function current(): ProductMapperInterface
    {
        return new ProductMapper($this->data[$this->index]);
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
        return key_exists($this->index, $this->data);
    }

    public function rewind(): void
    {
        $this->index = 0;
    }
}
