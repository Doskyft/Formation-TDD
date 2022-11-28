<?php

require_once __DIR__.'/Coordinates.php';
require_once __DIR__.'/Planet.php';

class Rover {

    public  const NORTH = 'north';
    public  const SOUTH = 'south';
    public  const EAST = 'east';
    public  const WEST = 'west';

    public const DIRECTIONS = [
        self::NORTH,
        self::EAST,
        self::WEST,
        self::SOUTH,
    ];

    private string $direction;
    private ?Planet $planet = null;

    public function __construct(
        private Coordinates $coordinates,
        string $direction,
    ) {
        $this->setDirection($direction);
    }

    public function coordinates(): Coordinates
    {
        return $this->coordinates;
    }

    public function forward(): void
    {
        switch ($this->getDirection()) {
            case self::NORTH: $this->coordinates()->setY($this->coordinates()->getY() + 1); break;
            case self::SOUTH: $this->coordinates()->setY($this->coordinates()->getY() - 1); break;
            case self::WEST: $this->coordinates()->setX($this->coordinates()->getX() - 1); break;
            case self::EAST: $this->coordinates()->setX($this->coordinates()->getX() + 1); break;
        }

        $this->wrapRoverMovesOnPlanetEdges();
    }

    public function wrapRoverMovesOnPlanetEdges(): void
    {
        if (null === $this->planet) {
            return;
        }

        if ($this->coordinates()->getY() > $this->planet->getHeight()) {
            $this->coordinates()->setY(0);
        }

        if ($this->coordinates()->getY() < 0) {
            $this->coordinates()->setY($this->planet->getHeight());
        }

        if ($this->coordinates()->getX() > $this->planet->getWidth()) {
            $this->coordinates()->setX(0);
        }

        if ($this->coordinates()->getX() < 0) {
            $this->coordinates()->setX($this->planet->getWidth());
        }
    }

    public function landOnPlanet(Planet $planet): void
    {
        $this->planet = $planet;
    }

    public function backward(): void
    {
        switch ($this->getDirection()) {
            case self::NORTH: $this->coordinates()->setY($this->coordinates()->getY() - 1); break;
            case self::SOUTH: $this->coordinates()->setY($this->coordinates()->getY() + 1); break;
            case self::WEST: $this->coordinates()->setX($this->coordinates()->getX() + 1); break;
            case self::EAST: $this->coordinates()->setX($this->coordinates()->getX() - 1); break;
        }

        $this->wrapRoverMovesOnPlanetEdges();
    }

    public function getDirection(): string
    {
        return $this->direction;
    }

    public function setDirection(string $direction): self
    {
        if (!in_array($direction, self::DIRECTIONS)) {
            $direction = self::NORTH;
        }

        $this->direction = $direction;

        return $this;
    }

    public function left(): void
    {
        switch ($this->getDirection()) {
            case self::NORTH: $this->setDirection(self::WEST); break;
            case self::WEST: $this->setDirection(self::SOUTH); break;
            case self::SOUTH: $this->setDirection(self::EAST); break;
            case self::EAST: $this->setDirection(self::NORTH); break;
        }
    }

    public function right(): void
    {
        switch ($this->getDirection()) {
            case self::NORTH: $this->setDirection(self::EAST); break;
            case self::EAST: $this->setDirection(self::SOUTH); break;
            case self::SOUTH: $this->setDirection(self::WEST); break;
            case self::WEST: $this->setDirection(self::NORTH); break;
        }
    }

    public function execInstructions(array $instructions): void
    {
        foreach ($instructions as $instruction) {
            switch ($instruction) {
                case 'f': $this->forward(); break;
                case 'b': $this->backward(); break;
                case 'l': $this->left(); break;
                case 'r': $this->right(); break;
            }
        }
    }
}