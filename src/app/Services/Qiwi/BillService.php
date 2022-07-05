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
     * @var BillPayments
     */
    protected BillPayments $billPayments;
    /**
     * @var string
     */
    private string $url;

    private string $apiKey;

    /**
     * BillService constructor.
     * @param string $testKey
     * @throws \ErrorException
     */
    public function __construct(string $testKey = '')
    {
        $this->apiKey = $testKey !== '' ? $testKey : config('services.qiwi.secret');
        $this->url = config('services.qiwi.url');
        $this->billPayments = new BillPayments($this->apiKey);
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

    /**
     * Отменить заказ (пользовательский метод)
     * @param string $billId
     * @return array|mixed
     */
    final public function cancelBIllCustom(string $billId): array
    {
        return Http::withToken($this->apiKey)->post($this->url."bills/$billId/reject")->json();
    }
}
