<?php

declare(strict_types=1);

namespace bravik\Shared\Domain;

use bravik\Shared\Domain\Events\DomainEventInterface;

interface EventDispatcherInterface
{
    public function dispatch(DomainEventInterface ...$events): void;
}
