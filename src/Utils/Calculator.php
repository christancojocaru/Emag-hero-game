<?php


namespace App\Utils;


class Calculator
{
    /**
     * Not used anymore
     * @param int $what
     * @param int $of
     * @return int
     */
    public static function getPercent(int $what, int $of): int
    {
        $random = (bool) rand(0, 1);
        $percent = ($what / 100) * $of;
        return $random ? ceil($percent) : floor($percent);
    }
}