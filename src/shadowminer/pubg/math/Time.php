<?php

declare(strict_types=1);

namespace ShadowMiner\pubg\math;

/**
 * Class Time
 * @package pubg\math
 */
class Time {

    /**
     * @param int $time
     * @return string
     */
    public static function calculateTime(int $time): string {
        return gmdate("i:s", $time); 
    }
}
