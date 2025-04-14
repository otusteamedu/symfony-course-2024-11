<?php

declare(strict_types=1);

namespace bravik\Sales\Domain\UseCases\Queries\Invoices\GeneratePaymentLink;

final readonly class Result
{
    public function __construct(
        public string $paymentLink
    ) {
    }
}