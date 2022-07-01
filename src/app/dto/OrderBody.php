<?php

namespace App\dto;

use App\Services\Constants\Common;

class OrderBody
{
    /**
     * @var string
     */
    private string $billId;
    /**
     * @var array
     */
    private array $products;
    /**
     * @var float
     */
    private float $totalPrice;
    /**
     * @var string
     */
    private string $payUrl;
    /**
     * @var string
     */
    private string $userId;

    /**
     * @return string
     */
    public function getBillId(): string
    {
        return $this->billId;
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @return float
     */
    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    /**
     * @return string
     */
    public function getPayUrl(): string
    {
        return $this->payUrl;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }



    public function __construct(string $billId, array $products, float $totalPrice, string $payUrl, string $userId) {
        $this->billId = $billId;
        $this->payUrl = $payUrl;
        $this->products = $products;
        $this->totalPrice = $totalPrice;
        $this->userId = $userId;
    }

    /**
     * @return array
     */
    final public function toArray(): array {
        return [
            Common::BILL_ID => $this->billId,
            Common::PRODUCTS => $this->products,
            Common::TOTAL_PRICE => $this->totalPrice,
            Common::PAY_URL => $this->payUrl,
            Common::USER_ID => $this->userId,
        ];
    }
}
