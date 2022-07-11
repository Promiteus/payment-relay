<?php

namespace App\tests\Unit;

use App\dto\OrderBody;
use App\dto\ProductItem;
use App\Models\Product;
use App\Services\Constants\Common;
use Tests\TestCase;

/**
 * Class OrderBodyTest
 * @package App\tests\Unit
 */
class OrderBodyTest extends TestCase
{
    /**
     *
     */
    public function testFromBodySet(): void
    {
        $orderBody = app(OrderBody::class);
        $this->console("\nТестирование метода OrderBody fromBodySet() с нормальными аргументами....");

        $products = [
            [
                Common::CODE => "10-00",
                Common::COUNT => 1,
                Common::NAME => "Товар 1",
                Product::PRICE => 100.0,
            ],
            [
                Common::CODE => "10-01",
                Common::COUNT => 1,
                Common::NAME => "Товар 2",
                Product::PRICE => 600.0,
            ],
        ];

        $order = [
            Common::USER_ID => "300",
            Common::BILL_ID => "000",
            Common::TOTAL_PRICE => 700.0,
            Common::PRODUCTS => $products,
            Common::COMMENT => "Товары 1 и 2 - за 700 руб",
            Common::EMAIL => "dr.romanm@yandex.ru",
        ];

        $result = $orderBody->fromBodySet($order);

        $this->assertInstanceOf(OrderBody::class, $result);

        $this->assertSame('300', $result->getUserId());
        $this->assertSame('000', $result->getBillId());
        $this->assertSame(700.0, $result->getTotalPrice());
        $this->assertSame('Товары 1 и 2 - за 700 руб', $result->getComment());
        $this->assertSame('dr.romanm@yandex.ru', $result->getEmail());
        $this->assertIsArray($result->getProducts());
        $this->assertInstanceOf(ProductItem::class, $result->getProducts()[0]);

        $this->okMsg();
    }

    public function testFromBodySetBadArgs(): void
    {
        $orderBody = app(OrderBody::class);
        $this->console("\nТестирование метода OrderBody fromBodySet() с плохими аргументами....");

        $products = [];

        $order = [
            Common::USER_ID => "300",
            Common::TOTAL_PRICE => 700.0,
            Common::PRODUCTS => $products,
        ];

        $result = $orderBody->fromBodySet($order);

        $this->assertInstanceOf(OrderBody::class, $result);

        $this->assertSame('300', $result->getUserId());
        $this->assertSame('', $result->getBillId());
        $this->assertSame(700.0, $result->getTotalPrice());
        $this->assertSame('', $result->getComment());
        $this->assertSame('', $result->getEmail());
        $this->assertIsArray($result->getProducts());
        $this->assertEmpty($result->getProducts());

        $this->okMsg();
    }

    /**
     *
     */
    public function testToArray(): void
    {
        $orderBody = app(OrderBody::class);
        $this->console("\nТестирование метода OrderBody toArray() с нормальными аргументами....");

        $products = [
            [
                Common::CODE => "10-00",
                Common::COUNT => 1,
                Common::NAME => "Товар 1",
                Product::PRICE => 100.0,
            ],
            [
                Common::CODE => "10-01",
                Common::COUNT => 1,
                Common::NAME => "Товар 2",
                Product::PRICE => 600.0,
            ],
        ];

        $order = [
            Common::USER_ID => "300",
            Common::BILL_ID => "000",
            Common::TOTAL_PRICE => 700.0,
            Common::PRODUCTS => $products,
         Common::COMMENT => "Товары 1 и 2 - за 700 руб",
         Common::EMAIL => "dr.romanm@yandex.ru",
       ];

        $result = $orderBody->fromBodySet($order);

        $this->assertInstanceOf(OrderBody::class, $result);

        $productItems = [];
        foreach ($products as $product) {
            $productItems[] = (new ProductItem)->fromBodySet($product);
        }

        $expected = [
            Common::BILL_ID => '000',
            Common::AMOUNT => 700.0,
            Common::TOTAL_PRICE => 700.0,
            Common::PRODUCTS => $productItems,
            Common::USER_ID => '300',
            Common::COMMENT => 'Товары 1 и 2 - за 700 руб',
            Common::EMAIL => 'dr.romanm@yandex.ru',
        ];

        $this->assertEquals($expected, $result->toArray());

        $this->okMsg();
    }

    public function testToArrayBadArgs(): void
    {
        $orderBody = app(OrderBody::class);
        $this->console("\nТестирование метода OrderBody toArray() с плохими аргументами....");

        $order = [
            Common::USER_ID => "300",
            Common::BILL_ID => "000",
            Common::TOTAL_PRICE => 700.0,
            Common::PRODUCTS => [],
        ];

        $result = $orderBody->fromBodySet($order);

        $this->assertInstanceOf(OrderBody::class, $result);

        $productItems = [];

        $expected = [
            Common::BILL_ID => '000',
            Common::AMOUNT => 700.0,
            Common::TOTAL_PRICE => 700.0,
            Common::PRODUCTS => $productItems,
            Common::USER_ID => '300',
            Common::COMMENT => '',
            Common::EMAIL => '',
        ];

        $this->assertEquals($expected, $result->toArray());

        $this->okMsg();
    }


}
