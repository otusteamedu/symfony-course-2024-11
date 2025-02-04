<?php

namespace App\Infrastructure\Bus\Adapter;

use App\Domain\Bus\AddFollowersBusInterface;
use App\Domain\DTO\AddFollowersDTO;
use App\Infrastructure\Bus\AmqpExchangeEnum;
use App\Infrastructure\Bus\RabbitMqBus;

class AddFollowersRabbitMqBus implements AddFollowersBusInterface
{
    public function __construct(private readonly RabbitMqBus $rabbitMqBus)
    {
    }

    public function sendAddFollowersMessage(AddFollowersDTO $addFollowersDTO): bool
    {
        // Example 1. Multiple users per message
//        $messages = [$addFollowersDTO];

        // Example 2. One user per message
        $messages = [];
        for ($i = 0; $i < $addFollowersDTO->count; $i++) {
            $messages[] = new AddFollowersDTO($addFollowersDTO->userId, $addFollowersDTO->followerLogin."_$i", 1);
        }

        return $this->rabbitMqBus->publishMultipleToExchange(AmqpExchangeEnum::AddFollowers, $messages);
    }
}
