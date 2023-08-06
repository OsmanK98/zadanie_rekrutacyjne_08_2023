<?php

namespace App\Service;

use App\Dto\Input\CreateEmployeeDto;
use App\Dto\Output\CreatedEmployeeDto;
use App\Dto\Output\EmployeeCreatedOutput;
use App\Entity\Employee;
use App\Entity\Pesel;
use App\Infrastructure\Exception\InvalidInputException;
use App\Repository\EmployeeRepository;
use App\Validator\EmployeeValidator;
use DateTime;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EmployeeCreator
{
    public function __construct(
        private EmployeeRepository $employeeRepository,
        private GenderProvider $genderProvider,
        private EmployeeValidator $employeeValidator,
        private UserPasswordHasherInterface $hasher
    ) {
    }

    public function add(CreateEmployeeDto $dto)
    {
        $employee = new Employee();
        $gender = $this->genderProvider->getByName($dto->getGender());
        if (!$gender) {
            throw new InvalidInputException('Invalid gender');
        }

        if (!$this->employeeValidator->validateUniqueFields(
            $dto->getEmail(),
            $dto->getPesel(),
        )) {
            throw new InvalidInputException('Employee exist in system.');
        }

        $employee->add(
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

        return new EmployeeCreatedOutput(
            $employee->getId()
        );
    }
}
