<?php

declare(strict_types=1);

namespace ShadowMiner\pubg\event;

use pocketmine\event\plugin\PluginEvent;
use pocketmine\Player;
use ShadowMiner\pubg\arena\Arena;
use ShadowMiner\pubg\PubG;

/**
 * Class PlayerArenaWinEvent
 * @package pubg\event
 */
class PlayerArenaWinEvent extends PluginEvent {

    /** @var null $handlerList */
    public static $handlerList = \null;

    /** @var Player $player */
    protected $player;

    /** @var Arena $arena */
    protected $arena;

    /**
     * PlayerArenaWinEvent constructor.
     * @param PubG $plugin
     * @param Player $player
     * @param Arena $arena
     */
    public function __construct(PubG $plugin, Player $player, Arena $arena) {
        $this->player = $player;
        $this->arena = $arena;
        parent::__construct($plugin);
    }

    /**
     * @return Player $arena
     */
    public function getPlayer(): Player {
        return $this->player;
    }

    /**
     * @return Arena $arena
     */
    public function getArena(): Arena {
        return $this->arena;
    }
}
