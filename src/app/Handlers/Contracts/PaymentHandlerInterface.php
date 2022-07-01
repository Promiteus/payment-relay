<?php

namespace App\Handlers\Contracts;

use App\dto\OrderBody;
use App\dto\PayResponse;

/**
 * Interface PaymentHandlerInterface
 */
interface PaymentHandlerInterface
{
    public function handleBill(OrderBody $order): PayResponse;
}
