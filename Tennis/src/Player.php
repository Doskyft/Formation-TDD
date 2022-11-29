<?php

declare(strict_types=1);

class Player
{
    private string $points = "love";
    private int $winningGame = 0;

    public function getCurrentPoint(): string
    {
        return $this->points;
    }

    public function score(): void
    {
        $this->points = match ($this->getCurrentPoint()) {
            "love" => "15",
            "15" => "30",
            "30" => "40",
            "40" => "Win",
        };

        if ("Win" === $this->getCurrentPoint()) {
            $this->winGame();
        }
    }

    public function setPoint(string $points): self
    {
        $this->points = $points;

        return $this;
    }

    public function winGame(): void
    {
        $this->winningGame++;
    }

    public function getWinningGame(): int
    {
        return $this->winningGame;
    }
}