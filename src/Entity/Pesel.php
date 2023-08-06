<?php

namespace App\Entity;

use App\Infrastructure\Exception\InvalidInputException;
use Doctrine\ORM\Mapping as ORM;
use Exception;

#[ORM\Embeddable]
class Pesel
{
    private const POSITION_WEIGHTS = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3];
    private const PESEL_LENGTH = 11;
    private const GENDER_CONFIG = [
        'M' => ['1', '3', '5', '7', '9'],
        'K' => ['0', '2', '4', '6', '8']
    ];
    private const GENDER_NUMBER = 9;

    #[ORM\Column(type: 'string')]
    private string $pesel;

    public function __construct(
        string $pesel,
        string $gender,
        bool $shouldValidateConditions = true
    ) {
        if (!$this->checkPesel(
            $pesel,
            $gender,
            $shouldValidateConditions
        )) {
            throw new InvalidInputException('Invalid PESEL.');
        }

        $this->pesel = $pesel;
    }

    /**
     * @throws Exception
     */
    public function checkPesel(
        string $pesel,
        string $gender,
        bool $shouldValidateConditions
    ): bool {
        if (!$shouldValidateConditions) {
            return true;
        }

        if (!$this->validateLengthOfPesel($pesel)) {
            return false;
        }

        if (!$this->validateGenderInPesel($pesel, $gender)) {
            return false;
        }

        if (!$this->validateSumControlOfPesel($pesel)) {
            return false;
        }

        return true;
    }

    private function validateLengthOfPesel(string $pesel): bool
    {
        return strlen($pesel) === self::PESEL_LENGTH;
    }

    private function validateGenderInPesel(string $pesel, string $gender): bool
    {
        $genderConfig = self::GENDER_CONFIG[$gender] ?? null;
        if (!$genderConfig) {
            throw new Exception();
        }

        return in_array($pesel[self::GENDER_NUMBER], $genderConfig);
    }

    private function validateSumControlOfPesel(string $pesel): bool
    {
        $sum = 0;
        for ($i = 0; $i < self::PESEL_LENGTH - 1; $i++) {
            $sum += self::POSITION_WEIGHTS[$i] * $pesel[$i];
        }

        $controlSum = 10 - $sum % 10;
        $controlNr = ($controlSum === 10) ? 0 : $controlSum;

        return $controlNr === (int)$pesel[10];
    }

    public function toString(): string
    {
        return $this->pesel;
    }
}

