<?php

namespace App\Services\Qiwi\Contracts;

use Qiwi\Api\BillPayments;

/**
 * Interface BillInterface
 * @package App\Services\Qiwi\Contracts
 */
interface BillInterface
{
    public function getBillPayment(): BillPayments;
    public function getPublicKey(): string;
}
