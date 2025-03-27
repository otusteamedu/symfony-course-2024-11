<?php

declare(strict_types=1);

namespace bravik\Sales\Domain\UseCases\Commands\Subscriptions\SubmitInvoice;

use DomainException;

final class InvalidSubscriptionStateException extends DomainException
{
}