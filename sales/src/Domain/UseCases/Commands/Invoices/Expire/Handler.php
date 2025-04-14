<?php

declare(strict_types=1);

namespace bravik\Sales\Domain\UseCases\Commands\Invoices\Expire;

use bravik\Sales\Domain\Exceptions\InvoiceIsNotAwaitingPaymentException;
use bravik\Sales\Domain\Exceptions\NotFoundException;
use bravik\Sales\Domain\Repositories\InvoicesRepositoryInterface;
use bravik\Shared\Domain\EventDispatcherInterface;
use bravik\Shared\Domain\FlusherInterface;

final class Handler
{
    public function __construct(
        private readonly InvoicesRepositoryInterface $invoicesRepository,
        private readonly FlusherInterface $flusher,
        private readonly EventDispatcherInterface $dispatcher,
    ) {
    }

    /**
     * @throws NotFoundException
     * @throws InvoiceIsNotAwaitingPaymentException
     */
    public function handle(Command $command): void
    {
        $invoice = $this->invoicesRepository->get($command->invoiceId);

        $invoice->expire();

        $this->flusher->flush();

        $this->dispatcher->dispatch(...$invoice->releaseEvents());
    }
}