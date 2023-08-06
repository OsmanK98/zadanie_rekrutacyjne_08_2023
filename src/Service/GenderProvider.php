<?php

namespace App\Service;

use App\Entity\Gender;
use App\Repository\GenderRepository;

class GenderProvider
{
    public function __construct(
        private GenderRepository $genderRepository
    ) {
    }

    public function getByName(string $name): ?Gender
    {
        return $this->genderRepository->findOneBy(['name' => $name]);
    }
}
