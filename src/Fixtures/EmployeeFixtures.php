<?php

namespace App\Fixtures;

use App\Entity\Employee;
use App\Entity\Gender;
use App\Entity\Pesel;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EmployeeFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
    ) {
    }

    public function load(ObjectManager $manager)
    {
        $genderMale = new Gender('M');
        $genderFemale = new Gender('K');

        $manager->persist($genderMale);
        $manager->persist($genderFemale);

        $employee = new Employee();
        $hashedPassword = $this->hasher->hashPassword($employee, 'zaq1@WSX');
        $employee->add(
            'Jan',
            'Kowalski',
            'test@test.pl',
            $hashedPassword,
            new DateTime(),
            $genderMale,
            new Pesel(
                '98112000000',
                $genderMale->getName(),
                false
            )
        );

        $manager->persist($employee);

        $faker = Factory::create();
        for ($i = 0; $i < 100; $i++) {
            $employee = new Employee();
            $gender = $faker->randomElement([$genderMale, $genderFemale]);
            $hashedPassword = $this->hasher->hashPassword($employee, 'zaq1@WSX');
            $birthdate = $faker->dateTimeBetween('-60 years', '-20 years');

            $employee->add(
                $faker->firstName,
                $faker->lastName,
                $faker->email,
                $hashedPassword,
                $birthdate,
                $gender,
                new Pesel(
                    $faker->numberBetween(10000000000, 99999999999),
                    $gender->getName(),
                    false
                )
            );

            $manager->persist($employee);
        }

        $manager->flush();
    }
}
