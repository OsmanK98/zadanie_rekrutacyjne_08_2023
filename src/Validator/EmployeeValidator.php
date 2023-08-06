<?php

namespace App\Validator;

use App\Repository\EmployeeRepository;
use Symfony\Component\Uid\Uuid;

class EmployeeValidator
{
    public function __construct(
        private EmployeeRepository $employeeRepository
    ) {
    }

    public function validateUniqueFields(
        string $email,
        string $pesel,
        Uuid $excludeId = null
    ): bool {
        if ($this->employeeRepository->checkUniqueOfField('email', $email, $excludeId)) {
            return false;
        }

        if ($this->employeeRepository->checkUniqueOfField('pesel.pesel', $pesel, $excludeId)) {
            return false;
        }

        return true;
    }
}
