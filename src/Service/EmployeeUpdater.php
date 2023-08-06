<?php

namespace App\Service;

use App\Dto\Input\CreateEmployeeDto;
use App\Dto\Input\UpdateEmployeeDto;
use App\Dto\Output\CreatedEmployeeDto;
use App\Dto\Output\EmployeeCreatedOutput;
use App\Dto\Output\EmployeeUpdatedOutput;
use App\Entity\Employee;
use App\Entity\Pesel;
use App\Infrastructure\Exception\InvalidInputException;
use App\Repository\EmployeeRepository;
use App\Validator\EmployeeValidator;
use DateTime;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class EmployeeUpdater
{
    public function __construct(
        private EmployeeProvider $employeeProvider,
        private EmployeeRepository $employeeRepository,
        private GenderProvider $genderProvider,
        private EmployeeValidator $employeeValidator,
        private UserPasswordHasherInterface $hasher
    ) {
    }

    public function edit(Uuid $employeeId, UpdateEmployeeDto $dto)
    {
        $employee = $this->employeeProvider->find($employeeId);
        if (!$employee) {
            throw new InvalidInputException('Employee not exists.');
        }

        $gender = $this->genderProvider->getByName($dto->getGender());
        if (!$gender) {
            throw new InvalidInputException('Invalid gender');
        }

        if (!$this->employeeValidator->validateUniqueFields(
            $dto->getEmail(),
            $dto->getPesel(),
            $employeeId
        )) {
            throw new InvalidInputException('Employee exists in system.');
        }

        $employee->edit(
            $dto->getFirstName(),
            $dto->getLastName(),
            $dto->getEmail(),
            $this->hasher->hashPassword($employee, $dto->getPassword()),
            new DateTime($dto->getBirthdate()),
            $gender,
            new Pesel(
                $dto->getPesel(),
                $gender->getName(),
            ),
        );

        $this->employeeRepository->save($employee);

        return new EmployeeUpdatedOutput(
            $employee->getId()
        );
    }
}
