<?php

declare(strict_types=1);

namespace bravik\Shared\Domain;

/**
 * Interface for flushing pending UnitOfWork changes.
 */
interface FlusherInterface
{

    public function flush(?string $className = null): void;
}
