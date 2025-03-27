<?php

declare(strict_types=1);

namespace bravik\Sales\Domain\UseCases\Queries\Invoices\GeneratePaymentLink;

use DomainException;

final class NoInvoicesAvailableException extends DomainException
{
}