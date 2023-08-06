<?php

namespace App\Dto\Input;

class EmployeeListQueryDto
{
    public function __construct(
        private ?int $page,
        private ?int $limit,
        private ?string $searchValue,
        private ?string $sortBy,
        private ?string $sortOrder,
    ) {
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getSearchValue(): ?string
    {
        return $this->searchValue;
    }

    public function getSortBy(): ?string
    {
        return $this->sortBy;
    }

    public function getSortOrder(): ?string
    {
        return $this->sortOrder;
    }
}
