<?php

require_once __DIR__.'/Coordinates.php';

class Planet {
    private ?Coordinates $obstacleCoordinates = null;

    public function __construct(private int $width, private int $height)
    {
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setObstacle(Coordinates $coordinates): void
    {
        $this->obstacleCoordinates = $coordinates;
    }

    public function getObstacle(): ?Coordinates
    {
        return $this->obstacleCoordinates;
    }
}