<?php

namespace App\Dto\Output;

use JsonSerializable;

class EmployeeListOutput implements JsonSerializable
{
    public function __construct(
        private string $id,
        private string $firstName,
        private string $lastName,
        private string $email,
        private string $gender,
        private string $pesel,
        private string $birthdate,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getPesel(): string
    {
        return $this->pesel;
    }

    public function getBirthdate(): string
    {
        return $this->birthdate;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'gender' => $this->gender,
            'pesel' => $this->pesel,
            'birthdate' => $this->birthdate,
        ];
    }
}
