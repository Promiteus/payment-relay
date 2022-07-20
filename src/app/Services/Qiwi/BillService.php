<?php

namespace App\Services\Qiwi;

use App\Services\Qiwi\Contracts\BillInterface;
use Illuminate\Support\Facades\Http;
use Qiwi\Api\BillPayments;


/**
 * Class BillService
 * @package App\Services\Qiwi
 */
class BillService implements BillInterface
{
    /**
     * @var string
     */
    private string $url;

    private string $apiKey;
    /**
     * @var BillPayments
     */
    private BillPayments $billPayment;

    /**
     * BillService constructor.
     */
    public function __construct()
    {
        $this->apiKey = env('QIWI_SECRET_KEY');
        $this->billPayment = new BillPayments($this->apiKey);
        $this->url = env('QIWI_URL');
    }

    /**
     * @return BillPayments
     */
    final public function getBillPayment(): BillPayments
    {
        return $this->billPayment;
    }

    /**
     * @return string
     */
    final public function getPublicKey(): string {
        return config('services.qiwi.public');
    }

    /**
     * Отменить заказ (пользовательский метод)
     * @param string $billId
     * @return array
     */
    final public function cancelBIllCustom(string $billId): array
    {
        return Http::withToken($this->apiKey)->post($this->url."bills/$billId/reject")->json();
    }
}
