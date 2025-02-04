<?php

namespace App\Domain\Service;

use App\Domain\Bus\AddFollowersBusInterface;
use App\Domain\DTO\AddFollowersDTO;
use App\Domain\Entity\User;
use App\Domain\Model\CreateUserModel;
use App\Domain\ValueObject\CommunicationChannelEnum;

class FollowerService
{
    public function __construct(
        private readonly UserService $userService,
        private readonly SubscriptionService $subscriptionService,
        private readonly AddFollowersBusInterface $addFollowersBus,
    ) {

    }

    /**
     * TODO Не следует передавать в сервис модель! Модели должны использоваться только внутри сервисов
     *  В качестве параметров передаем либо примитивные значения, либо DTO
     */
    public function addFollowers(User $user, string $followerLoginPrefix, int $count): int
    {
        $createdFollowers = 0;
        for ($i = 0; $i < $count; $i++) {
            $login = "{$followerLoginPrefix}_$i";

            $channel = random_int(0, 2) === 1 ? CommunicationChannelEnum::Email : CommunicationChannelEnum::Phone;

            $model = new CreateUserModel(
                $login,
                match ($channel) {
                    CommunicationChannelEnum::Email => "{$login}@mail.ru",
                    CommunicationChannelEnum::Phone => '+'.str_pad((string)abs(crc32($login)), 10, '0'),
                },
                $channel,
                "{$login}_password",
            );

            $follower = $this->userService->create($model);
            $this->subscriptionService->addSubscription($user, $follower);
            $createdFollowers++;
        }

        return $createdFollowers;
    }

    // Same as addFollowers (for code not to break between branches)
    public function addFollowersSync(User $user, string $followerLoginPrefix, int $count): int
    {
        return $this->addFollowers($user, $followerLoginPrefix, $count);
    }

    public function addFollowersAsync(AddFollowersDTO $addFollowersDTO): int
    {
        return $this->addFollowersBus->sendAddFollowersMessage($addFollowersDTO) ? $addFollowersDTO->count : 0;
    }
}
