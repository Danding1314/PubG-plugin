<?php

declare(strict_types=1);

namespace shadowminer\pubg\math;

/**
 * Class Vector3
 * @package pubg\math
 */
class Vector3 extends \pocketmine\math\Vector3 {

    /**
     * @return string
     */
    public function __toString() {
        return "$this->x,$this->y,$this->z";
    }

    /**
     * @param string $string
     * @return Vector3
     */
    public static function fromString(string $string) {
        return new Vector3((int)explode(",", $string)[0], (int)explode(",", $string)[1], (int)explode(",", $string)[2]);
    }
}
