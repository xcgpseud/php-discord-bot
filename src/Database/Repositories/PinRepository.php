<?php

namespace PseudBot\Database\Repositories;

use PDO;
use PhpOption\None;
use PhpOption\Option;
use PhpOption\Some;
use PseudBot\Database\DbConn;
use PseudBot\Models\Pin;
use PseudBot\Traits\Singleton;

class PinRepository
{
    use Singleton;

    public function getAllForUser($userId): Option
    {
        $stmt = DbConn::get()->prepare("
            SELECT *
            FROM pins
            WHERE user_id = :user_id
            ORDER BY id ASC
        ");

        $stmt->execute(['user_id' => $userId]);

        $pinArrs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $out = [];
        foreach ($pinArrs as $pinArr) {
            $out[] = Pin::getFromArray($pinArr);
        }

        return Some::ensure($out);
    }

    public function getNthPinForUser(int $userId, int $n): Option
    {
        $pins = $this->getAllForUser($userId);

        if ($pins->isEmpty() || !isset($pins[$n])) {
            return None::create();
        }

        $pin = Pin::getFromArray($pins[$n]);
        return Some::ensure($pin);
    }

    public function createPin(int $userId, string $data): Option
    {
        $pins = $this->getAllForUser($userId);

        if ($pins->isEmpty() || count($pins->get()) >= getenv("MAX_PINS")) {
            return None::create();
        }

        $stmt = DbConn::get()->prepare("
            INSERT INTO pins(user_id, data, created)
            VALUES(:user_id, :data, :created)
        ");

        if ($stmt->execute([
            'user_id' => $userId,
            'data' => $data,
            'created' => date(getenv("DATE_FORMAT")),
        ])) {
            return Some::ensure($this->getLastPinForUser($userId));
        }

        return None::create();
    }

    public function getLastPinForUser(int $userId): Option
    {
        $stmt = DbConn::get()->prepare("
            SELECT * FROM pins
            WHERE user_id = :user_id
            ORDER BY id DESC
            LIMIT 1
        ");

        if ($stmt->execute([
            'user_id' => $userId,
        ])) {
            $pin = Pin::getFromArray($stmt->fetch(PDO::FETCH_ASSOC));
            return Some::ensure($pin);
        }

        return None::create();
    }
}
