<?php

namespace App\Tests\Employee;

use App\Dto\Input\CreateEmployeeDto;
use App\Entity\Employee;
use App\Entity\Gender;
use App\Entity\Pesel;
use DateTime;
use PHPUnit\Framework\TestCase;

class EmployeeTest extends TestCase
{
    /**
     * @test
     */
    public function AddNewEmployeeTest(): void
    {
        // given
        $data = $this->getEmployeeData();
        $dto = new CreateEmployeeDto(
            $data['firstName'],
            $data['lastName'],
            $data['email'],
            $data['password'],
            $data['password'],
            $data['birthdate'],
            $data['gender'],
            $data['pesel'],
        );

        // when
        $employee = new Employee();
        $employee->add(
            $dto->getFirstName(),
            $dto->getLastName(),
            $dto->getEmail(),
            $dto->getPassword(),
            new DateTime($dto->getBirthdate()),
            new Gender($data['gender']),
            new Pesel($dto->getPesel(), $dto->getGender(), false),
        );

        // then
        $this->assertEquals($dto->getFirstName(), $employee->getFirstName());
        $this->assertEquals($dto->getLastName(), $employee->getLastName());
        $this->assertEquals($dto->getEmail(), $employee->getEmail());
        $this->assertEquals($dto->getPassword(), $employee->getPassword());
        $this->assertEquals(new DateTime($dto->getBirthdate()), $employee->getBirthdate());
        $this->assertEquals($dto->getGender(), $employee->getGender()->getName());
        $this->assertEquals($dto->getPesel(), $employee->getPesel()->toString());
    }

    /**
     * @test
     */
    public function EditEmployeeTest(): void
    {
        // given
        $data = $this->getEmployeeData();
        $dto = new CreateEmployeeDto(
            $data['firstName'],
            $data['lastName'],
            $data['email'],
            $data['password'],
            $data['password'],
            $data['birthdate'],
            $data['gender'],
            $data['pesel'],
        );

        // when
        $employee = new Employee();
        $employee->edit(
            $dto->getFirstName(),
            $dto->getLastName(),
            $dto->getEmail(),
            $dto->getPassword(),
            new DateTime($dto->getBirthdate()),
            new Gender($data['gender']),
            new Pesel($dto->getPesel(), $dto->getGender(), false),
        );

        // then
        $this->assertEquals($dto->getFirstName(), $employee->getFirstName());
        $this->assertEquals($dto->getLastName(), $employee->getLastName());
        $this->assertEquals($dto->getEmail(), $employee->getEmail());
        $this->assertEquals($dto->getPassword(), $employee->getPassword());
        $this->assertEquals(new DateTime($dto->getBirthdate()), $employee->getBirthdate());
        $this->assertEquals($dto->getGender(), $employee->getGender()->getName());
        $this->assertEquals($dto->getPesel(), $employee->getPesel()->toString());
    }

    private function getEmployeeData(): array
    {
        return [
            'firstName' => 'Jan',
            'lastName' => 'Kowalski',
            'email' => 'kowalski.jan@gmail.com',
            'password' => 'zaq1@WSX',
            'birthdate' => '1998-11-10',
            'pesel' => '02271409862',
            'gender' => 'K'
        ];
    }
}
