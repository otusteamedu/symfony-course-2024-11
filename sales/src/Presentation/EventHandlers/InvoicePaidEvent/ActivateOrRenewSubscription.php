<?php

declare(strict_types=1);

namespace bravik\Sales\Presentation\EventHandlers\InvoicePaidEvent;

use bravik\Sales\Domain\Events\InvoicePaidEvent;
use bravik\Sales\Domain\UseCases\Commands\Subscriptions\SubmitInvoice\Command as SubmitInvoiceCommand;
use bravik\Sales\Domain\UseCases\Commands\Subscriptions\SubmitInvoice\Handler as SubmitInvoiceHandler;
use Exception;

/**
 * Этот обработчик события должен перехватить событие InvoicePaidEvent и вызвать действие SubmitInvoice над подпиской.
 */
class ActivateOrRenewSubscription
{
    public function __construct(
        private SubmitInvoiceHandler $submitInvoiceHandler
    ) {
    }

    public function handle(InvoicePaidEvent $event): void
    {
        try {
            $this->submitInvoiceHandler->handle(
                new SubmitInvoiceCommand(
                    invoiceId: $event->getAggregateId(),
                )
            );
        } catch (Exception $e) {

            // TODO Handle errors
            throw $e;
        }
    }
}