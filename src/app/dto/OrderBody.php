<?php

namespace App\dto;

use App\Services\Constants\Common;

/**
 * Class OrderBody
 * @package App\dto
 */
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
     * @var OrderBody
     */
    private static $instance = null;
    /**
     * @var string
     */
    private string $comment;
    /**
     * @var string
     */
    private string $email;

    /**
     * OrderBody constructor.
     */
    private function __construct() {}

    private function init() {
        $this->billId = '';
        $this->totalPrice = 0;
        $this->userId = '';
        $this->products = array();
        $this->comment = '';
        $this->email = '';
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

    /**
     * @param array $body
     * @return $this
     */
    public function fromBodySet(array $body): OrderBody {
        $this->init();

        $this->billId = array_key_exists(Common::BILL_ID, $body) && $body[Common::BILL_ID] ? $body[Common::BILL_ID] : $this->billId;
        $this->totalPrice = array_key_exists(Common::TOTAL_PRICE, $body) && $body[Common::TOTAL_PRICE] ? $body[Common::TOTAL_PRICE] : $this->totalPrice;
        $this->comment = array_key_exists(Common::COMMENT, $body) && $body[Common::COMMENT] ? $body[Common::COMMENT] : $this->comment;
        $this->email = array_key_exists(Common::EMAIL, $body) && $body[Common::EMAIL] ? $body[Common::EMAIL] : $this->email;
        $this->userId = array_key_exists(Common::USER_ID, $body) && $body[Common::USER_ID] ? $body[Common::USER_ID] : $this->userId;
        $productsArray = array_key_exists(Common::PRODUCTS, $body) && is_array($body[Common::PRODUCTS]) && $body[Common::PRODUCTS] ? $body[Common::PRODUCTS] : $this->products;

        $this->products = [];
        if ((count($productsArray) !== 0) && (is_array($productsArray))) {
            foreach ($productsArray as $productItem) {
                $this->products[] = (new ProductItem())->fromBodySet($productItem);
            }
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
            Common::TOTAL_PRICE => $this->totalPrice,
            Common::PRODUCTS => $this->products,
            Common::USER_ID => $this->userId,
            Common::COMMENT => $this->comment,
            Common::EMAIL => $this->email,
        ];
    }

}
