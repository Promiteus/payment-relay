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
     * BillService constructor.
     * @param BillPayments $billPayments
     */
    public function __construct(BillPayments $billPayments)
    {
        //$this->apiKey = env('QIWI_SECRET_KEY');
        $this->url = env('QIWI_URL');
    }

    /**
     * @return BillPayments
     */
    final public function getBillPayment(): BillPayments
    {
        return app(BillPayments::class, ['key' => env('QIWI_SECRET_KEY')]);
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
