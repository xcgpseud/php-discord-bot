<?php

namespace PseudBot\CommandHandlers;

use Discord\Discord;
use Discord\Parts\Channel\Message;
use PseudBot\Traits\Singleton;

class PingPongHandler
{
    use Singleton;

    public function handle(Message $msg, Discord $discord): void
    {
        $msg->reply("Pong!");
    }
}
