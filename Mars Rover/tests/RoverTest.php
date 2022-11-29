<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__.'/../src/Rover.php';
require_once __DIR__.'/../src/Planet.php';
require_once __DIR__.'/../src/ObstacleException.php';

/**
 * Access rover new coordinates
 * Rover moving forward
 * Rover moving backward
 *
 * En tant que Rover, je dois pouvoir changer de direction
 * En tant que Rover, je dois pouvoir avancer dans toutes les directions Nord, Sud, Ouest et Est
 * Gérer la direction, N,S,O,E
 */

class RoverTest extends TestCase {
    public function testAccessingRoverCoordinates(): void
    {
        $rover = new Rover(
            new Coordinates(1, 1),
            Rover::NORTH
        );

        self::assertSame(Rover::NORTH, $rover->getDirection());
        self::assertSame(1, $rover->coordinates()->getX());
        self::assertSame(1, $rover->coordinates()->getY());
    }

    public function testNewCoordinatesAfterMovingForwardIsCorrect(): void
    {
        $rover = new Rover(
            new Coordinates(1, 1),
            Rover::NORTH
        );

        $rover->forward();

        self::assertSame(1, $rover->coordinates()->getX());
        self::assertSame(2, $rover->coordinates()->getY());
    }

    public function testNewCoordinatesAfterMovingBackwardIsCorrect(): void
    {
        $rover = new Rover(
            new Coordinates(1, 1),
            Rover::NORTH
        );

        $rover->backward();

        self::assertSame(1, $rover->coordinates()->getX());
        self::assertSame(0, $rover->coordinates()->getY());
    }

    public function testNewDirectionAfterTurningLeftIsCorrect(): void
    {
        $rover = new Rover(
            new Coordinates(1, 1),
            Rover::NORTH
        );

        $rover->left();
        self::assertSame(Rover::WEST, $rover->getDirection());

        $rover->left();
        self::assertSame(Rover::SOUTH, $rover->getDirection());

        $rover->left();
        self::assertSame(Rover::EAST, $rover->getDirection());

        $rover->left();
        self::assertSame(Rover::NORTH, $rover->getDirection());
    }

    public function testNewDirectionAfterTurningRightIsCorrect(): void
    {
        $rover = new Rover(
            new Coordinates(1, 1),
            Rover::NORTH
        );

        $rover->right();
        self::assertSame(Rover::EAST, $rover->getDirection());

        $rover->right();
        self::assertSame(Rover::SOUTH, $rover->getDirection());

        $rover->right();
        self::assertSame(Rover::WEST, $rover->getDirection());

        $rover->right();
        self::assertSame(Rover::NORTH, $rover->getDirection());
    }

    public function testMoveForwardBasedOnDirection(): void
    {
        $rover = new Rover(
            new Coordinates(1, 1),
            Rover::SOUTH
        );

        $rover->forward();

        self::assertSame(1, $rover->coordinates()->getX());
        self::assertSame(0, $rover->coordinates()->getY());

        $rover->left();
        $rover->forward();

        self::assertSame(2, $rover->coordinates()->getX());
        self::assertSame(0, $rover->coordinates()->getY());
    }

    public function testMoveBackwardBasedOnDirection(): void
    {
        $rover = new Rover(
            new Coordinates(1, 1),
            Rover::WEST
        );

        $rover->backward();

        self::assertSame(2, $rover->coordinates()->getX());
        self::assertSame(1, $rover->coordinates()->getY());

        $rover->right();
        $rover->backward();

        self::assertSame(2, $rover->coordinates()->getX());
        self::assertSame(0, $rover->coordinates()->getY());
    }

    public function testMoveRoverBasedOnInstruction(): void
    {
        $rover = new Rover(
            new Coordinates(6, 6),
            Rover::NORTH
        );

        $instructions = [
            'f', 'f', 'l', 'l', 'f', 'r', 'b',
        ];

        $rover->execInstructions($instructions);

        self::assertSame(7, $rover->coordinates()->getX());
        self::assertSame(7, $rover->coordinates()->getY());
    }

    public function testRoverAsExceededMapLimits(): void
    {
        $planet = new Planet(8,10);

        $rover = new Rover(
            new Coordinates(8, 10),
            Rover::NORTH
        );

        $rover->landOnPlanet($planet);

        $rover->forward();
        self::assertSame(8, $rover->coordinates()->getX());
        self::assertSame(0, $rover->coordinates()->getY());

        $rover->right();
        $rover->forward();
        self::assertSame(0, $rover->coordinates()->getX());
        self::assertSame(0, $rover->coordinates()->getY());

        $rover->right();
        $rover->forward();
        self::assertSame(0, $rover->coordinates()->getX());
        self::assertSame(10, $rover->coordinates()->getY());

        $rover->right();
        $rover->forward();
        self::assertSame(8, $rover->coordinates()->getX());
        self::assertSame(10, $rover->coordinates()->getY());
    }

    public function testRoverReportsEncounteredObstacleAndAbortMovingInstructions(): void
    {
        $planet = new Planet(8,10);

        $planet->setObstacle(new Coordinates(2, 3));

        $rover = new Rover(
            new Coordinates(1, 1),
            Rover::NORTH
        );

        $rover->landOnPlanet($planet);

        $instructions = [
            'f', 'f', 'r', 'f', 'f', 'f',
        ];

        self::expectException(ObstacleException::class);
        self::expectExceptionMessage('Obstacle encountered at coordinates (2, 3)');
        $rover->execInstructions($instructions);
        self::assertSame(1, $rover->coordinates()->getX());
        self::assertSame(3, $rover->coordinates()->getY());
    }
}