<?php

declare(strict_types=1);

namespace bravik\Sales\Domain\UseCases\Commands\Invoices\Pay;

use bravik\Shared\Domain\Model\OId;

final readonly class Command
{
    public function __construct(
        public OId $invoiceId,
        public string $transactionId,
    ) {
    }
}