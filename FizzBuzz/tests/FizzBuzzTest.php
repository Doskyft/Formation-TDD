<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * FizzBuzz
 *
 * Prend un nombre en entré et doit lister toutes les nombres, en remplaçant
 * Les multiple de 3 par Fizz
 * Les multiple de 5 par Buzz
 * Les multiple de 3 et 5 (de 15) par FizzBuzz
 *
 * TODO:
 * - testReplaceMultipleOfThreeByFizz
 * - testReplaceMultipleOfFiveByBuzz
 *
 */
class FizzBuzzTest extends TestCase
{

    public function testItReturnsAListWithOneEntry(): void
    {
        self::assertCount(1, $this->fizzBuzz(1));
    }

    public function testItReturnsAListWithManyEntries(): void
    {
        self::assertCount(20, $this->fizzBuzz(20));
    }

    public function testItReturnsAListWithManyEntriesAndThreeConverted(): void
    {
        $fizz = $this->fizzBuzz(3);

        self::assertSame(1, $fizz[1]);
        self::assertSame(2, $fizz[2]);
        self::assertSame('Fizz', $fizz[3]);
    }

    public function testItConvertsMultipleOfThreeIntoFizz(): void
    {
        $fizz = $this->fizzBuzz(12);

        self::assertSame('Fizz', $fizz[3]);
        self::assertSame('Fizz', $fizz[6]);
        self::assertSame('Fizz', $fizz[9]);
        self::assertSame('Fizz', $fizz[12]);
    }

    public function testItConvertsMultipleOfFiveIntoBuzz(): void
    {
        $fizz = $this->fizzBuzz(10);

        self::assertSame('Buzz', $fizz[5]);
        self::assertSame('Buzz', $fizz[10]);
    }

    public function testItConvertsMultipleOfFiveAndThreeIntoBuzz(): void
    {
        $fizz = $this->fizzBuzz(45);

        self::assertSame('FizzBuzz', $fizz[15]);
        self::assertSame('FizzBuzz', $fizz[30]);
        self::assertSame('FizzBuzz', $fizz[45]);
    }

    public function fizzBuzz(int $endingInteger): array
    {
        $outputList = [];

        for ($i = 1; $i <= $endingInteger; $i++) {

            $outputList[$i] = '';

            if ($i % 3 === 0) {
                $outputList[$i] = 'Fizz';
            }

            if ($i % 5 === 0) {
                $outputList[$i] .= 'Buzz';
            }

            if ($outputList[$i] === '') {
                $outputList[$i] = $i;
            }
        }

        return $outputList;
    }
}