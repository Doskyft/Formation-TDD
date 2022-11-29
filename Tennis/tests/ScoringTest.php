<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/Player.php';
require_once __DIR__ . '/../src/TennisMatch.php';

/**
 * As a player I'm starting the game at love point
 * As a player I can score points following tennis counting rules
 * As a player I can win a game
 * As a player I can win a match
 * As a player I can win a game I have at least 4 points
 * As a player I can't win a game I don't have at least 2 points difference with my opponent
 * As a player I win the game if I score a point when I have 40 points and my opponent has less than 40 points
 * As a player if my opponent has the advantage, I should score first to be back to deuce
 * As a player if my opponent and I are deuce, I should take the Advantage and score again to win the game
 */
final class ScoringTest extends TestCase
{
    public function testPlayerStartTheGameAtLovePoint(): void
    {
        $player = new Player();

        self::assertSame('love', $player->getCurrentPoint());
    }

    public function testPlayerCanScorePointsFollowingTennisCountingRules(): void
    {
        $player = new Player();

        $player->score();
        self::assertSame('15', $player->getCurrentPoint());

        $player->score();
        self::assertSame('30', $player->getCurrentPoint());

        $player->score();
        self::assertSame('40', $player->getCurrentPoint());
    }

    public function testPlayerCanWinAGameWithAScoreUpper40(): void
    {
        $player = new Player();
        $player->score();
        $player->score();
        $player->score();
        $player->score();
        self::assertSame('Win', $player->getCurrentPoint());
    }

    //public function testPlayerCanWinAMatch(): void
    //{
    //    $player = new Player();
    //    $match = new TennisMatch();
    //
    //    $match->setWinner($player);
    //
    //    self::assertSame($player, $match->getWinner());
    //}

    public function testAPlayerCanWinAMatchWithAtLeastFourPoints(): void
    {
        $player = new Player();

        $match = new TennisMatch($player);

        $player->winGame();
        $player->winGame();
        $player->winGame();

        self::assertNull($match->getWinner());

        $player->winGame();

        self::assertSame($player, $match->getWinner());
    }

    public function testPlayerCannotWinGameWithoutTwoPointsDifferenceWithOpponent(): void
    {
        $player1 = new Player();
        $player2 = new Player();

        $match = new TennisMatch();

        $player1->winGame();
        $player2->winGame();

        $player1->winGame();
        $player2->winGame();

        $player1->winGame();
        $player2->winGame();

        $player1->winGame();
        self::assertNull($match->getWinner());

        $player1->winGame();
        self::assertSame($player1, $match->getWinner());
    }
}
