<?php

namespace App\Services\Qiwi;

use App\Services\Qiwi\Contracts\BillInterface;
use Qiwi\Api\BillPayments;


/**
 * Class BillService
 * @package App\Services\Qiwi
 */
class BillService implements BillInterface
{

    protected BillPayments $billPayments;

    /**
     * BillService constructor.
     * @param string $testKey
     * @throws \ErrorException
     */
    public function __construct(string $testKey = '')
    {
        $apiKey = $testKey !== '' ? $testKey : config('services.qiwi.secret');
        $this->billPayments = new BillPayments($apiKey);
    }

    /**
     * @return BillPayments
     */
    final public function getBillPayment(): BillPayments
    {
        return $this->billPayments;
    }

    /**
     * @return string
     */
    final public function getPublicKey(): string {
        return config('services.qiwi.public');
    }
}
