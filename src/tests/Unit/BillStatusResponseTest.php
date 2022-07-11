<?php

namespace App\dto;

use App\Services\Constants\Common;
use Tests\TestCase;


class BillStatusResponseTest extends TestCase
{
    private BillStatusResponse $billStatus;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->billStatus = BillStatusResponse::getInstance();
    }

    public function testInstanseOfBillStatusResponse() {
        $this->console("\nТестирование BillStatusResponse::getInstance() на предмет возврата экземпляра BillStatusResponse....");

        self::assertInstanceOf(BillStatusResponse::class, $this->billStatus);
        $this->okMsg();
    }

    public function testToArray()
    {
        $this->console("\nТестирование метода toArray() с нормальными аргументами....");
        $arg = [
            Common::PAY_URL => 'http://...',
            Common::BILL_ID => '000',
            Common::STATUS => [
                Common::VALUE => Common::WAITING_STATUS,
            ],
        ];

        $result = $this->billStatus->fromBodySet($arg);

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
        $this->console("\nТестирование метода toArray() с пустыми аргументами....");
        $arg = [
            Common::PAY_URL => '',
            Common::BILL_ID => '',
            Common::STATUS => [
                Common::VALUE => '',
            ],
        ];

        $result = $this->billStatus->fromBodySet($arg);

        $expected = [
            Common::PAY_URL => '',
            Common::BILL_ID => '',
            Common::STATUS =>  '',
        ];
        $this->assertEquals($expected, $result->toArray());

        $this->okMsg();
    }

    public function testInstanceOf() {
        $this->console("\nТестирование fromBodySet() на предмет возврата экземпляра BillStatusResponse....");

        $result = $this->billStatus->fromBodySet([]);
        $this->assertInstanceOf(BillStatusResponse::class, $result);

        $this->okMsg();
    }

    public function testFromBodySetOfEmptyArg()
    {
        $this->console("\nТестирование fromBodySet() с пустым аргументом....");

        $result = $this->billStatus->fromBodySet([]);

        $this->assertSame($result->getStatus(), '');
        $this->assertSame($result->getBillId(), '');
        $this->assertSame($result->getPayUrl(), '');

        $this->okMsg();
    }

    public function testFromBodySet()
    {
        $this->console("\nТестирование fromBodySet() с нормальным аргументом....");

        $arg = [
            Common::PAY_URL => 'http://...',
            Common::BILL_ID => '000',
            Common::STATUS => [
                Common::VALUE => Common::WAITING_STATUS,
            ],
        ];

        $result = $this->billStatus->fromBodySet($arg);

        $this->assertSame($result->getStatus(), Common::WAITING_STATUS);
        $this->assertSame($result->getBillId(), '000');
        $this->assertSame($result->getPayUrl(), 'http://...');

        $this->okMsg();
    }

    public function testFromBodySetWrongStatusFormat()
    {
        $this->console("\nТестирование fromBodySet() с искаженным форматом статуса....");

        $arg = [
            Common::PAY_URL => 'http://...',
            Common::BILL_ID => '000',
            Common::STATUS => Common::WAITING_STATUS,
        ];

        $result = $this->billStatus->fromBodySet($arg);

        $this->assertSame($result->getStatus(), '');
        $this->assertSame($result->getBillId(), '000');
        $this->assertSame($result->getPayUrl(), 'http://...');

        $this->okMsg();
    }
}
