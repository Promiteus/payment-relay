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
     * @param array $body
     * @return $this
     */
    public function fromBodySet(array $body): ProductItem {
        $this->count = $body[Common::COUNT] ?: $this->count;
        $this->code = $body[Common::CODE] ?: $this->code;
        $this->name = $body[Common::NAME] ?: $this->name;
        $this->price = $body[Invoice::PRICE] ?: $this->price;

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
