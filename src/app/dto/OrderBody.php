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
     * @var ProductItem[]
     */
    private array $products;
    /**
     * @var float
     */
    private float $totalPrice;
    /**
     * @var string
     */
    private string $userId;
    /**
     * @var OrderBody
     */
    private static $instance = null;

    /**
     * OrderBody constructor.
     */
    private function __construct()
    {
        $this->billId = '';
        $this->totalPrice = 0;
        $this->userId = '';
        $this->products = [];
    }

    /**
     * @return OrderBody
     */
    public static function getInstance(): OrderBody {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return string
     */
    public function getBillId(): string
    {
        return $this->billId;
    }

    /**
     * @return ProductItem[]
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
    public function getUserId(): string
    {
        return $this->userId;
    }

    public function fromBodySet(array $body): OrderBody {
        $this->billId = $body[Common::BILL_ID] ?: $this->billId;
        $this->totalPrice = $body[Common::TOTAL_PRICE] ?: $this->totalPrice;

        $userIdBody = $body[Common::USER_ID];
        $this->userId = $userIdBody ?: $this->userId;

        $productsArray = $body[Common::PRODUCTS];
        if ((count($productsArray) !== 0) && (is_array($productsArray))) {
            $this->products = collect($productsArray)->map(function ($item) {
                return (new ProductItem())->fromBodySet($item);
            })->toArray();
        }

        return $this;
    }

    /**
     * @return array
     */
    final public function toArray(): array {
        return [
            Common::BILL_ID => $this->billId,
            Common::AMOUNT => $this->totalPrice,
            Common::PRODUCTS => collect($this->products)->toArray(),
            Common::USER_ID => $this->userId,
        ];
    }
}
