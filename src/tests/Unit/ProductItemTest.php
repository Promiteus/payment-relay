<?php

namespace App\dto;

use App\Models\Invoice;
use App\Services\Constants\Common;
use Tests\TestCase;

/**
 * Class ProductItemTest
 * @package App\dto
 */
class ProductItemTest extends TestCase
{
    /**
     * @var ProductItem
     */
    private ProductItem $productItem;

    /**
     * ProductItemTest constructor.
     * @param string|null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->productItem = new ProductItem();
    }

    /**
     *
     */
    public function testToArray()
    {
        $this->console("\nТестирование метода ProductItem toArray() с нормальными аргументами....");

        $body = [
            Invoice::PRICE => 100.0,
            Common::NAME => 'Product 1',
            Common::CODE => '0900',
            Common::COUNT => 2,
        ];
        $result = $this->productItem->fromBodySet($body);

        $this->assertInstanceOf(ProductItem::class, $result);

        $this->assertEquals($body, $result->toArray());

        $this->okMsg();
    }

    /**
     *
     */
    public function testToArrayBadArgs()
    {
        $this->console("\nТестирование метода ProductItem toArray() с плохими аргументами....");

        $body = [
        ];
        $result = $this->productItem->fromBodySet($body);

        $this->assertInstanceOf(ProductItem::class, $result);

        $expected = [
            Invoice::PRICE => 0,
            Common::NAME => '',
            Common::CODE => '',
            Common::COUNT => 1,
        ];


        $this->assertEquals($expected, $result->toArray());

        $this->okMsg();
    }

    /**
     *
     */
    public function testFromBodySet()
    {
        $this->console("\nТестирование метода ProductItem fromBodySet() с нормальными аргументами....");

        $body = [
            Invoice::PRICE => 100.0,
            Common::NAME => 'Product 1',
            Common::CODE => '0900',
            Common::COUNT => 2,
        ];
        $result = $this->productItem->fromBodySet($body);

        $this->assertInstanceOf(ProductItem::class, $result);
        $this->assertSame(100.0, $result->getPrice());
        $this->assertSame('Product 1', $result->getName());
        $this->assertSame('0900', $result->getCode());
        $this->assertSame(2, $result->getCount());

        $this->okMsg();
    }

    /**
     *
     */
    public function testFromBodySetBadArgs()
    {
        $this->console("\nТестирование метода ProductItem fromBodySet() с плохими аргументами....");

        $body = [];
        $result = $this->productItem->fromBodySet($body);

        $this->assertInstanceOf(ProductItem::class, $result);
        $this->assertSame(0.0, $result->getPrice());
        $this->assertSame('', $result->getName());
        $this->assertSame('', $result->getCode());
        $this->assertSame(1, $result->getCount());

        $this->okMsg();
    }
}
