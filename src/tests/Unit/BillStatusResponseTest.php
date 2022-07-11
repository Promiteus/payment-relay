<?php

namespace App\dto;

use App\Services\Constants\Common;
use Tests\TestCase;


class BillStatusResponseTest extends TestCase
{

    public function testToArray()
    {
        $billStatus = app(BillStatusResponse::class);
        $this->console("\nТестирование метода toArray() с нормальными аргументами....");
        $arg = [
            Common::PAY_URL => 'http://...',
            Common::BILL_ID => '000',
            Common::STATUS => [
                Common::VALUE => Common::WAITING_STATUS,
            ],
        ];

        $result = $billStatus->fromBodySet($arg);

        $expected = [
            Common::PAY_URL => 'http://...',
            Common::BILL_ID => '000',
            Common::STATUS =>  Common::WAITING_STATUS,
        ];
        $this->assertEquals($expected, $result->toArray());

        $this->okMsg();
    }

    public function testToArrayEmptyArg()
    {
        $billStatus = app(BillStatusResponse::class);

        $this->console("\nТестирование метода toArray() с пустыми аргументами....");
        $arg = [
            Common::PAY_URL => '',
            Common::BILL_ID => '',
            Common::STATUS => [
                Common::VALUE => '',
            ],
        ];

        $result = $billStatus->fromBodySet($arg);

        $expected = [
            Common::PAY_URL => '',
            Common::BILL_ID => '',
            Common::STATUS =>  '',
        ];
        $this->assertEquals($expected, $result->toArray());

        $this->okMsg();
    }

    public function testInstanceOf() {
        $billStatus = app(BillStatusResponse::class);
        $this->console("\nТестирование fromBodySet() на предмет возврата экземпляра BillStatusResponse....");

        $result = $billStatus->fromBodySet([]);
        $this->assertInstanceOf(BillStatusResponse::class, $result);

        $this->okMsg();
    }

    public function testFromBodySetOfEmptyArg()
    {
        $billStatus = app(BillStatusResponse::class);
        $this->console("\nТестирование fromBodySet() с пустым аргументом....");

        $result = $billStatus->fromBodySet([]);

        $this->assertSame($result->getStatus(), '');
        $this->assertSame($result->getBillId(), '');
        $this->assertSame($result->getPayUrl(), '');

        $this->okMsg();
    }

    public function testFromBodySet()
    {
        $billStatus = app(BillStatusResponse::class);
        $this->console("\nТестирование fromBodySet() с нормальным аргументом....");

        $arg = [
            Common::PAY_URL => 'http://...',
            Common::BILL_ID => '000',
            Common::STATUS => [
                Common::VALUE => Common::WAITING_STATUS,
            ],
        ];

        $result = $billStatus->fromBodySet($arg);

        $this->assertSame($result->getStatus(), Common::WAITING_STATUS);
        $this->assertSame($result->getBillId(), '000');
        $this->assertSame($result->getPayUrl(), 'http://...');

        $this->okMsg();
    }

    public function testFromBodySetWrongStatusFormat()
    {
        $billStatus = app(BillStatusResponse::class);
        $this->console("\nТестирование fromBodySet() с искаженным форматом статуса....");

        $arg = [
            Common::PAY_URL => 'http://...',
            Common::BILL_ID => '000',
            Common::STATUS => Common::WAITING_STATUS,
        ];

        $result = $billStatus->fromBodySet($arg);

        $this->assertSame($result->getStatus(), '');
        $this->assertSame($result->getBillId(), '000');
        $this->assertSame($result->getPayUrl(), 'http://...');

        $this->okMsg();
    }
}
