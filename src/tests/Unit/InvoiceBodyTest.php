<?php

namespace App\dto;

use App\Services\Constants\Common;
use Tests\TestCase;

class InvoiceBodyTest extends TestCase
{

    public function testToArray()
    {
        $invoiceBody = app(InvoiceBody::class);
        $this->console("\nТестирование метода InvoiceBody toArray() с нормальными аргументами....");

        $invoice = [
            Common::BILL_ID => '000',
            Common::AMOUNT => [
                Common::CURRENCY => 'RUB',
                Common::VALUE => 100,
            ],
            Common::STATUS => [
                Common::VALUE => Common::WAITING_STATUS,
            ],
            Common::COMMENT => 'any comment',
            Common::PAY_URL => 'http://...',
        ];

        $result = $invoiceBody->fromBodySet($invoice);

        $expected = [
            Common::BILL_ID => $invoice[Common::BILL_ID],
            Common::AMOUNT => 100,
            Common::CURRENCY => 'RUB',
            Common::COMMENT => $invoice[Common::COMMENT],
            Common::EXPIRATION_DATE => '',
            Common::STATUS => Common::WAITING_STATUS,
            Common::PAY_URL => 'http://...',
        ];

        $this->assertEquals($expected, $result->toArray());

        $this->okMsg();
    }

    public function testToArrayBadArgs()
    {
        $invoiceBody = app(InvoiceBody::class);
        $this->console("\nТестирование метода InvoiceBody toArray() с плохими аргументами валюты, суммы и статуса....");

        $invoice = [
            Common::BILL_ID => '000',
            Common::CURRENCY => 'RUB',
            Common::VALUE => 100,
            Common::STATUS =>  Common::WAITING_STATUS,
            Common::COMMENT => 'any comment',
            Common::PAY_URL => 'http://...',
        ];

        $result = $invoiceBody->fromBodySet($invoice);

        $expected = [
            Common::BILL_ID => $invoice[Common::BILL_ID],
            Common::AMOUNT => 0,
            Common::CURRENCY => 'RUB',
            Common::COMMENT => $invoice[Common::COMMENT],
            Common::EXPIRATION_DATE => '',
            Common::STATUS => '',
            Common::PAY_URL => 'http://...',
        ];

        $this->assertEquals($expected, $result->toArray());

        $this->okMsg();
    }

    public function testFromBodySet()
    {
        $invoiceBody = app(InvoiceBody::class);
        $this->console("\nТестирование метода InvoiceBody fromBodySet() с нормальными аргументами....");

        $invoice = [
            Common::BILL_ID => '000',
            Common::AMOUNT => [
                Common::CURRENCY => 'RUB',
                Common::VALUE => 100,
            ],
            Common::STATUS => [
                Common::VALUE => Common::WAITING_STATUS,
            ],
            Common::COMMENT => 'any comment',
            Common::PAY_URL => 'http://...',
        ];

        $result = $invoiceBody->fromBodySet($invoice);

        $this->assertSame($result->getBillId(), '000');
        $this->assertSame($result->getPayUrl(), 'http://...');
        $this->assertSame($result->getStatus(), Common::WAITING_STATUS,);
        $this->assertSame($result->getComment(), 'any comment');
        $this->assertSame($result->getAmount(), 100.0);
        $this->assertSame($result->getCurrency(), 'RUB');

        $this->okMsg();
    }

    public function testFromBodySetBadArgs()
    {
        $invoiceBody = app(InvoiceBody::class);
        $this->console("\nТестирование метода InvoiceBody fromBodySet() с плохими аргументами....");

        $invoice = [
            Common::BILL_ID => '000',
            Common::AMOUNT => 100.0,
            Common::STATUS =>  Common::WAITING_STATUS,
            Common::COMMENT => 'any comment',
        ];

        $result = $invoiceBody->fromBodySet($invoice);

        $this->assertSame($result->getBillId(), '000');
        $this->assertSame($result->getPayUrl(), '');
        $this->assertSame($result->getStatus(), '',);
        $this->assertSame($result->getComment(), 'any comment');
        $this->assertSame($result->getAmount(), 0.0);
        $this->assertSame($result->getCurrency(), 'RUB');

        $this->okMsg();
    }

}
