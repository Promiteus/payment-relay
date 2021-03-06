<?php


namespace App\dto;

use App\Models\Invoice;
use App\Services\Constants\Common;

/**
 * array ['code' => '90-444', 'count' => 1, 'name' => 'товар 1', 'price' => 100.0]
 * Class ProductItem
 * @package App\dto
 */
class ProductItem
{
    /**
     * @var string
     */
    private string $code;
    /**
     * @var int
     */
    private int $count;
    /**
     * @var string
     */
    private string $name;
    /**
     * @var float
     */
    private float $price;

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * ProductItem constructor.
     */
    public function __construct()
    {
        $this->code = '';
        $this->price = 0;
        $this->count = 1;
        $this->name = '';
    }


    /**
     * @param $body
     * @return $this
     */
    public function fromBodySet($body): ProductItem {
        $this->count = array_key_exists(Common::COUNT, $body) ? $body[Common::COUNT] : $this->count;
        $this->code = array_key_exists(Common::CODE, $body) ? $body[Common::CODE] : $this->code;
        $this->name = array_key_exists(Common::NAME, $body) ? $body[Common::NAME] : $this->name;
        $this->price = array_key_exists(Invoice::PRICE, $body) ? $body[Invoice::PRICE] : $this->price;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array {
        return [
            Invoice::PRICE => $this->price,
            Common::NAME => $this->name,
            Common::CODE => $this->code,
            Common::COUNT => $this->count,
        ];
    }

}
