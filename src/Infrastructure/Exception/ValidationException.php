<?php

namespace App\Infrastructure\Exception;

class ValidationException extends \Exception
{
    private array $validationErrors = [];

    public function setValidationErrors(array $validationErrors): void
    {
        $this->validationErrors = $validationErrors;
    }

    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }
}
