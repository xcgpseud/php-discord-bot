<?php

namespace PseudBot\Traits;

use PseudBot\CommandHandlers\PingPongHandler;
use PseudBot\Database\Repositories\PinRepository;
use PseudBot\MessageRouter;

/**
 * Trait Singleton
 * @package PseudBot\Traits
 *
 * @mixin MessageRouter
 */
trait Singleton
{
    private static self $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
