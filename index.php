<?php

function calculLengthOfVector(int $aX, int $aY, int $bX, int $bY): int
{
    return sqrt((($aX - $bX) ** 2) + (($aY - $bY) ** 2));
}