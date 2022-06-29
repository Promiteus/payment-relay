<?php

namespace App\Handlers\Contracts;

use App\dto\PayResponse;

/**
 * Interface PaymentHandlerInterface
 */
interface PaymentHandlerInterface
{
    public function handleBill(array $order): PayResponse;
}
