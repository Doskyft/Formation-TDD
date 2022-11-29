<?php declare(strict_types=1);

namespace tests;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/index.php';

final class VectorTest extends TestCase
{

    /**
     * - Les deux positions sont égales
     * - Le point à des coordonnées non null
     * - Pour deux points données il nous calcule bien la distance désirée
     * - Pour listes de points données il nous calcule bien les distances désirées
     * - Résultat absolu
     * - Est ce que le vecteur est vertical
     * - Est ce que le vecteur est horizontal
     */

    public function testReturnZeroIfFirstPosIsEqualToSecondPos(): void
    {
        $x = 50;
        $y = 50;

        $length = calculLengthOfVector($x, $y, $x, $y);

        $this->assertSame(0, $length);
    }
}
