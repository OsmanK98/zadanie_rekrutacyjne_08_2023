<?php

namespace App\Dto\Input;

use App\Dto\Dto;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateEmployeeDto implements Dto
{
    #[Assert\NotBlank(message: 'First name cannot be blank.')]
    private string $firstName;

    #[Assert\NotBlank(message: 'Last name cannot be blank.')]
    private string $lastName;

    #[Assert\NotBlank(message: 'Email cannot be blank.')]
    private string $email;

    #[Assert\NotBlank(message: 'Password cannot be blank.')]
    #[Assert\Length(
        min: 6,
        minMessage: 'Password must be at least 6 characters long.'
    )]
    #[Assert\Regex(
        pattern: "/^(?=.*[A-Z])(?=.*\d).+$/",
        message: 'Password must contain at least one uppercase letter and one digit.'
    )]
    private string $password;

    #[Assert\NotBlank(message: 'Repeated password cannot be blank.')]
    #[Assert\Expression('this.getPassword() == value', message: 'Passwords do not match.')]
    private string $repeatedPassword;

    #[Assert\Date(message: 'Invalid date format for birthdate.')]
    private string $birthdate;

    #[Assert\NotBlank(message: 'Gender cannot be blank.')]
    private string $gender;

    #[Assert\NotBlank(message: 'PESEL cannot be blank.')]
    private string $pesel;

    public function __construct(
        string $firstName,
        string $lastName,
        string $email,
        string $password,
        string $repeatedPassword,
        string $birthdate,
        string $gender,
        string $pesel
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->repeatedPassword = $repeatedPassword;
        $this->birthdate = $birthdate;
        $this->gender = $gender;
        $this->pesel = $pesel;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getBirthdate(): string
    {
        return $this->birthdate;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getPesel(): string
    {
        return $this->pesel;
    }

    public function getRepeatedPassword(): string
    {
        return $this->repeatedPassword;
    }
}
