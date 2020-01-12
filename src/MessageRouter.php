<?php

namespace PseudBot;

use Discord\Discord;
use Discord\Parts\Channel\Message;
use PseudBot\CommandHandlers\PingPongHandler;
use PseudBot\CommandHandlers\PinHandler;
use PseudBot\Traits\Singleton;

class MessageRouter
{
    use Singleton;

    private array $routes = [
        'ping' => PingPongHandler::class,
        'pin' => PinHandler::class,
    ];

    public function routeMessage(Message $msg, Discord $discord)
    {
        if ($msg->author->id === $discord->user->id) {
            return;
        }

        $args = explode(' ', $msg->content);

        if ($this->isCommand($args[0])) {
            $cmd = substr($args[0], strlen(getenv("PREFIX")));
            if (isset($this->routes[$cmd])) {
                $handler = call_user_func("{$this->routes[$cmd]}::getInstance");
                $handler->handle($msg, $discord);
            }
        }
    }

    private function isCommand(string $first): bool
    {
        return substr($first, 0, strlen(getenv("PREFIX")));
    }
}
