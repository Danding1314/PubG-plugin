<?php

declare(strict_types=1);

namespace ShadowMiner\pubg\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use ShadowMiner\pubg\arena\Arena;
use ShadowMiner\pubg\PubG;

/**
 * Class PubGCommand
 * @package pubg\commands
 */
class PubGCommand extends Command implements PluginIdentifiableCommand {

    /** @var PubG $plugin */
    protected $plugin;

    /**
     * PubGCommand constructor.
     * @param PubG $plugin
     */
    public function __construct(PubG $plugin) {
        $this->plugin = $plugin;
        parent::__construct("pubg", "PubG commands", \null, ["pg"]);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return mixed|void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if(!$sender->hasPermission("pg.cmd")) {
            $sender->sendMessage("§cYou have not permissions to use this command!");
            return;
        }
        if(!isset($args[0])) {
            $sender->sendMessage("§cUsage: §7/pg help");
            return;
        }

        switch ($args[0]) {
            case "help":
                if(!$sender->hasPermission("pg.cmd.help")) {
                    $sender->sendMessage("§cYou have not permissions to use this command!");
                    break;
                }
                $sender->sendMessage("§a> PubG commands:\n" .
                    "§7/pg help : Displays list of PubG commands\n".
                    "§7/pg create : Create PubG arena\n".
                    "§7/pg remove : Remove PubG arena\n".
                    "§7/pg set : Set PubG arena\n".
                    "§7/pg arenas : Displays list of arenas");

                break;
            case "create":
                if(!$sender->hasPermission("pg.cmd.create")) {
                    $sender->sendMessage("§cYou have not permissions to use this command!");
                    break;
                }
                if(!isset($args[1])) {
                    $sender->sendMessage("§cUsage: §7/pg create <arenaName>");
                    break;
                }
                if(isset($this->plugin->arenas[$args[1]])) {
                    $sender->sendMessage("§c> Arena $args[1] already exists!");
                    break;
                }
                $this->plugin->arenas[$args[1]] = new Arena($this->plugin, []);
                $sender->sendMessage("§a> Arena $args[1] created!");
                break;
            case "remove":
                if(!$sender->hasPermission("pg.cmd.remove")) {
                    $sender->sendMessage("§cYou have not permissions to use this command!");
                    break;
                }
                if(!isset($args[1])) {
                    $sender->sendMessage("§cUsage: §7/pg remove <arenaName>");
                    break;
                }
                if(!isset($this->plugin->arenas[$args[1]])) {
                    $sender->sendMessage("§c> Arena $args[1] was not found!");
                    break;
                }

                /** @var Arena $arena */
                $arena = $this->plugin->arenas[$args[1]];

                foreach ($arena->players as $player) {
                    $player->teleport($this->plugin->getServer()->getDefaultLevel()->getSpawnLocation());
                }

                if(is_file($file = $this->plugin->getDataFolder() . "arenas" . DIRECTORY_SEPARATOR . $args[1] . ".yml")) unlink($file);
                unset($this->plugin->arenas[$args[1]]);

                $sender->sendMessage("§a> Arena removed!");
                break;
            case "set":
                if(!$sender->hasPermission("pg.cmd.set")) {
                    $sender->sendMessage("§cYou have not permissions to use this command!");
                    break;
                }
                if(!$sender instanceof Player) {
                    $sender->sendMessage("§c> This command can be used only in-game!");
                    break;
                }
                if(!isset($args[1])) {
                    $sender->sendMessage("§cUsage: §7/pg set <arenaName>");
                    break;
                }
                if(isset($this->plugin->setters[$sender->getName()])) {
                    $sender->sendMessage("§c> You are already in setup mode!");
                    break;
                }
                if(!isset($this->plugin->arenas[$args[1]])) {
                    $sender->sendMessage("§c> Arena $args[1] does not found!");
                    break;
                }
                $sender->sendMessage("§a> You are joined setup mode.\n".
                    "§7- use §lhelp §r§7to display available commands\n"  .
                    "§7- or §ldone §r§7to leave setup mode");
                $this->plugin->setters[$sender->getName()] = $this->plugin->arenas[$args[1]];
                break;
            case "arenas":
                if(!$sender->hasPermission("pg.cmd.arenas")) {
                    $sender->sendMessage("§cYou have not permissions to use this command!");
                    break;
                }
                if(count($this->plugin->arenas) === 0) {
                    $sender->sendMessage("§6> There are 0 arenas.");
                    break;
                }
                $list = "§7> Arenas:\n";
                foreach ($this->plugin->arenas as $name => $arena) {
                    if($arena->setup) {
                        $list .= "§7- $name : §cdisabled\n";
                    }
                    else {
                        $list .= "§7- $name : §aenabled\n";
                    }
                }
                $sender->sendMessage($list);
                break;
            default:
                if(!$sender->hasPermission("pg.cmd.help")) {
                    $sender->sendMessage("§cYou have not permissions to use this command!");
                    break;
                }
                $sender->sendMessage("§cUsage: §7/pg help");
                break;
        }

    }

    /**
     * @return SkyWars|Plugin $plugin
     */
    public function getPlugin(): Plugin {
        return $this->plugin;
    }

}
