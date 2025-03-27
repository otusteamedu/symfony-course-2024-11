<?php

declare(strict_types=1);

namespace bravik\Sales\Tests\Domain\UseCases\Commands\Invoices\Expire;

use bravik\Sales\Domain\Exceptions\InvoiceIsNotAwaitingPaymentException;
use bravik\Sales\Domain\Model\Invoice\Invoice;
use bravik\Sales\Domain\Repositories\InvoicesRepositoryInterface;
use bravik\Sales\Domain\UseCases\Commands\Invoices\Expire\Command;
use bravik\Sales\Domain\UseCases\Commands\Invoices\Expire\Handler;
use bravik\Shared\Domain\EventDispatcherInterface;
use bravik\Shared\Domain\FlusherInterface;
use bravik\Shared\Domain\Model\OId;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(Handler::class)]
final class ExpireTest extends TestCase
{
    private Handler $handler;
    private InvoicesRepositoryInterface|MockObject $invoicesRepository;
    private FlusherInterface|MockObject $flusher;
    private EventDispatcherInterface|MockObject $dispatcher;

    protected function setUp(): void
    {
        $this->invoicesRepository = $this->createMock(InvoicesRepositoryInterface::class);
        $this->flusher = $this->createMock(FlusherInterface::class);
        $this->dispatcher = $this->createMock(EventDispatcherInterface::class);

        $this->handler = new Handler(
            $this->invoicesRepository,
            $this->flusher,
            $this->dispatcher
        );
    }

    public function testSuccess(): void
    {
        $invoiceId = OId::next();
        $command = new Command(invoiceId: $invoiceId);

        $invoice = $this->createMock(Invoice::class);
        $invoice
            ->expects($this->once())
            ->method('expire');

        $invoice
            ->expects($this->once())
            ->method('releaseEvents')
            ->willReturn([]);

        $this->invoicesRepository
            ->expects($this->once())
            ->method('get')
            ->with($command->invoiceId)
            ->willReturn($invoice);

        $this->flusher
            ->expects($this->once())
            ->method('flush');

        $this->dispatcher
            ->expects($this->once())
            ->method('dispatch');

        $this->handler->handle($command);
    }

    public function testFailsWhenInvoiceNotAwaitingPayment(): void
    {
        $invoiceId = OId::next();
        $command = new Command(invoiceId: $invoiceId);

        $invoice = $this->createMock(Invoice::class);
        $invoice
            ->expects($this->once())
            ->method('expire')
            ->willThrowException(new InvoiceIsNotAwaitingPaymentException());

        $this->invoicesRepository
            ->expects($this->once())
            ->method('get')
            ->with($command->invoiceId)
            ->willReturn($invoice);

        $this->expectException(InvoiceIsNotAwaitingPaymentException::class);
        $this->handler->handle($command);
    }
}