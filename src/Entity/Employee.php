<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
class Employee implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private $id;

    #[ORM\Column(type: "string", length: 255)]
    private string $firstName;

    #[ORM\Column(type: "string", length: 255)]
    private string $lastName;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private string $email;

    #[ORM\Column(type: "string", length: 255)]
    private string $password;

    #[ORM\Column(type: "date")]
    private DateTime $birthdate;

    #[ORM\ManyToOne(targetEntity: Gender::class)]
    #[ORM\JoinColumn(name: "gender_id", referencedColumnName: "id")]
    private Gender $gender;

    #[ORM\Embedded(class: Pesel::class, columnPrefix: false)]
    private Pesel $pesel;

    public function add(
        string $firstName,
        string $lastName,
        string $email,
        string $password,
        DateTime $birthdate,
        Gender $gender,
        Pesel $pesel,
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->birthdate = $birthdate;
        $this->gender = $gender;
        $this->pesel = $pesel;
    }

    public function edit(
        string $firstName,
        string $lastName,
        string $email,
        string $password,
        DateTime $birthdate,
        Gender $gender,
        Pesel $pesel,
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->birthdate = $birthdate;
        $this->gender = $gender;
        $this->pesel = $pesel;
    }

    public function getId()
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getBirthdate(): DateTime
    {
        return $this->birthdate;
    }

    public function getGender(): Gender
    {
        return $this->gender;
    }

    public function getPesel(): Pesel
    {
        return $this->pesel;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return [];
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
