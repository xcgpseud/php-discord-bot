<?php

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\Parts\User\Game;
use Dotenv\Dotenv;
use PseudBot\MessageRouter;

require_once "vendor/autoload.php";

$de = Dotenv::createImmutable(__DIR__);
$de->load();

$discord = new Discord([
    'token' => getenv('TOKEN'),
]);

$discord->on('ready', function ($discord) {
    echo "Bot ready." . PHP_EOL;
});

//$game = $discord->factory(Game::class, [
//    'name' => 'Chilling out...',
//]);
//$discord->updatePresence($game);

$discord->on('message', function (Message $msg) use ($discord) {
    MessageRouter::getInstance()->routeMessage($msg, $discord);
});

$discord->run();
