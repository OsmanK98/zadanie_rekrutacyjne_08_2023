<?php

namespace App\Tests\Pesel;

use App\Entity\Pesel;
use App\Infrastructure\Exception\InvalidInputException;
use PHPUnit\Framework\TestCase;

class PeselTest extends TestCase
{
    /**
     * @test
     * @dataProvider peselNegativeScenarious
     */
    public function checkPeselNegativeTest(array $data): void
    {
        $this->expectException(InvalidInputException::class);

        new Pesel($data['pesel'], $data['gender']);
    }

    /**
     * @test
     */
    public function checkPeselPositiveTest(): void
    {
        $this->expectNotToPerformAssertions();

        new Pesel('90090515836', 'M');
    }


    private function peselNegativeScenarious(): array
    {
        return [
            'Pesel needs 11 digits' => [
                [
                    'pesel' => '12345',
                    'gender' => 'M'
                ]
            ],
            'Incorrect digit of man gender' => [
                [
                    'pesel' => '00000000000',
                    'gender' => 'M'
                ]
            ],
            'Incorrect digit of woman gender' => [
                [
                    'pesel' => '00000000010',
                    'gender' => 'K'
                ]
            ],
            'Incorrect sum control' => [
                [
                    'pesel' => '11111111111',
                    'gender' => 'K'
                ]
            ],
        ];
    }
}
