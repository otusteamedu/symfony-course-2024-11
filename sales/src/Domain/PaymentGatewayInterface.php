<?php

declare(strict_types=1);

namespace bravik\Sales\Domain;

interface PaymentGatewayInterface
{
    public function getPaymentLink(string $invoiceId): string;
}