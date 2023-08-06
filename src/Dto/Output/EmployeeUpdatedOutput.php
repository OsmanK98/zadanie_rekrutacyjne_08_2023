<?php

namespace App\Dto\Output;

use JsonSerializable;

class EmployeeUpdatedOutput implements JsonSerializable
{
    public function __construct(
        private string $id
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
