<?php

namespace App\Jobs;

use App\Handlers\Qiwi\PaymentHandler;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Class RequestAndUpdateInvoiceStatus
 * @package App\Jobs
 */
class RequestAndUpdateInvoiceStatus implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    private string $billId;

    /**
     * Время в секундах, по окончанию которого данное задание больше не уникально
     * @var int
     */
    public $uniqueFor = 600;

    /**
     * RequestAndUpdateInvoiceStatus constructor.
     * @param string $billId
     */
    public function __construct(string $billId)
    {
        $this->billId = $billId;
    }

    /**
     * Уникальный идентификатор задания
     *
     * @return string
     */
    final public function uniqueId(): string
    {
        return $this->billId;
    }

    /**
     * @param PaymentHandler $paymentHandler
     */
    final public function handle(PaymentHandler $paymentHandler): void
    {
       // Log::info("test: ".$this->billId);
         $result = $paymentHandler->getBillStatus($this->billId);
         if ($result->getError() !== '') {
             Log::error(__CLASS__.': '.$result->getError());
         }
    }
}
