<?php


namespace App\dto;

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
        $this->price = $body[Common::AMOUNT] ?: $this->price;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array {
        return [
            Common::AMOUNT => $this->price,
            Common::NAME => $this->name,
            Common::CODE => $this->code,
            Common::COUNT => $this->count,
        ];
    }

}
