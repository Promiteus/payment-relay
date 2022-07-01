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
    private string $userId;
    /**
     * @var string
     */
    private string $comment;
    /**
     * @var string
     */
    private string $currency;
    /**
     * @var string
     */
    private string $email;

    public function __construct()
    {
        $this->billId = '';
        $this->totalPrice = 0;
        $this->comment = '';
        $this->userId = '';
        $this->products = [];
        $this->email = '';
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }


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
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
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
        $this->totalPrice = $body[Common::AMOUNT] ?: $this->totalPrice;
        $this->currency = $body[Common::CURRENCY] ?: $this->currency;
        $this->comment = $body[Common::COMMENT] ?: $this->comment;
        $this->email = $body[Common::EMAIL] ?: $this->email;

        $userIdBody = $body[Common::CUSTOM_FIELDS][Common::USER_ID];
        $this->userId = $userIdBody ?: $this->userId;

        $productsArray = $body[Common::CUSTOM_FIELDS][Common::PRODUCTS];
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
            Common::CURRENCY => 'RUB',
            Common::COMMENT => '',
            Common::EXPIRATION_DATE => $this->bill->getBillPayment()->getLifetimeByDay(1),
            Common::EMAIL => '',
            Common::CUSTOM_FIELDS => [
                Common::PRODUCTS => $this->products,
                Common::USER_ID => $this->userId,
            ]
        ];
    }
}
