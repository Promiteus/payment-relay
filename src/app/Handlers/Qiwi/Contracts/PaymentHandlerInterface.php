<?php

namespace App\Handlers\Qiwi\Contracts;

use App\dto\PayResponse;

/**
 * Interface PaymentHandlerInterface
 */
interface PaymentHandlerInterface
{
    public function handleBill(array $order): PayResponse;
}
