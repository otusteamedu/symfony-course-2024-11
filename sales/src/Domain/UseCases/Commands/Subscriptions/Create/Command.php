<?php

declare(strict_types=1);

namespace bravik\Sales\Domain\UseCases\Commands\Subscriptions\Create;

use DateTimeImmutable;
use bravik\Shared\Domain\Model\OId;

final readonly class Command
{
    public function __construct(
        public OId $customerId,
        public OId $productId,
        public DateTimeImmutable $startDate,
    ) {
    }
}