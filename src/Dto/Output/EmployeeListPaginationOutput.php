<?php

namespace App\Dto\Output;

use JsonSerializable;

class EmployeeListPaginationOutput implements JsonSerializable
{
    public function __construct(
        /** @var EmployeeListOutput[] $items */
        private array $items,
        private string $limit,
        private string $page,
        private string $total,
    ) {
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getLimit(): string
    {
        return $this->limit;
    }

    public function getPage(): string
    {
        return $this->page;
    }

    public function getTotal(): string
    {
        return $this->total;
    }

    public function jsonSerialize(): array
    {
        return [
            'items' => $this->items,
            'page' => $this->page,
            'limit' => $this->limit,
            'total' => $this->total,
        ];
    }
}
