<?php

declare(strict_types=1);

class TennisMatch
{
    private const MINIMUM_POINTS_TO_WIN_MATCH = 4;

    private Player $firstPlayer;
    private Player $secondPlayer;

    public function __construct(Player $firstPlayer, Player $secondPlayer)
    {
        $this->firstPlayer = $firstPlayer;
        $this->secondPlayer = $secondPlayer;
    }

    public function getFirstPlayer(): Player
    {
        return $this->firstPlayer;
    }

    public function getSecondPlayer(): Player
    {
        return $this->secondPlayer;
    }

    public function getWinner(): ?Player
    {
        if (self::MINIMUM_POINTS_TO_WIN_MATCH === $this->firstPlayer->getWinningGame()) {
            return $this->firstPlayer;
        }

        if (self::MINIMUM_POINTS_TO_WIN_MATCH === $this->secondPlayer->getWinningGame()) {
            return $this->secondPlayer;
        }

        return null;
    }
}