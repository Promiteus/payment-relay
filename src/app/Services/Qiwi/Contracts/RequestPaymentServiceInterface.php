<?php

namespace App\Services\Qiwi\Contracts;

use App\dto\PayResponse;
use Illuminate\Http\JsonResponse;

interface RequestPaymentServiceInterface
{
    public function createBill(array $body): PayResponse;
    public function cancelBill(string $billId): PayResponse;
    public function getBillInfo(string $billId): PayResponse;
    public function billStatusNotify(array $body): JsonResponse;
}
