<?php

namespace PseudBot\CommandHandlers;

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\Parts\Embed\Embed;
use PDO;
use PhpOption\None;
use PhpOption\Option;
use PhpOption\Some;
use PseudBot\Database\DbConn;
use PseudBot\Database\Repositories\PinRepository;
use PseudBot\Models\Pin;
use PseudBot\Traits\Singleton;

class PinHandler
{
    use Singleton;

    private const CREATE = "create";
    private const DELETE = "delete";
    private const UPDATE = "update";
    private const GET = "get";
    private const GET_ALL = "all";

    public function handle(Message $msg, Discord $discord): void
    {
        $data = array_slice(explode(' ', $msg->content), 1);
        if (count($data) < 1) {
            $msg->reply("Not enough arguments.");
        }
        switch ($data[0]) {
            case self::CREATE:
                $this->create($data, $msg, $discord);
                break;
            case self::GET_ALL:
                $allPins = $this->getAll($msg->author->id);
                if ($allPins->isEmpty()) {
                    $msg->reply("You have no pins.");
                }
                $newMsg = $discord->factory(Message::class);
                foreach ($allPins->get() as $pin) {
                    $newMsg->embeds[] = $this->getPinEmbed($discord, $msg, $pin);
                }
                $msg->channel->sendMessage($newMsg);
                break;
        }
    }

    private function getPinEmbed(Discord $discord, Message $msg, Pin $pin)
    {
        $embed = $discord->factory(Embed::class)->default;
        $embed->title = "Pin, by {$msg->author->username}";
        $embed->color = 5;
        $embed->description = $pin->getData() ?? '';
        return $embed;
    }

    private function create(array $data, Message $msg, Discord $discord): void
    {
        $userId = $msg->author->id;
        $pinData = implode(' ', array_slice($data, 1));
        $pin = PinRepository::getInstance()->createPin($userId, $pinData);

        if ($pin->isEmpty()) {
            return;
        }

        $embed = $this->getPinEmbed($discord, $msg, $pin->get());
        $msg->channel->sendMessage('', false, $embed);
    }
}
