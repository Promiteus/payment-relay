<?php


namespace App\Services\Qiwi;


use App\Services\Qiwi\Contracts\BillInterface;
use Qiwi\Api\BillPayments;

/**
 * Class BillService
 * @package App\Services\Qiwi
 */
class BillService implements BillInterface
{
    public const BILL_ID = 'billId';
    public const AMOUNT = 'amount';
    public const CURRENCY = 'currency';
    public const COMMENT = 'comment';
    public const EXPIRATION_DATE = 'expirationDateTime';
    public const EMAIL = 'email';
    public const ACCOUNT = 'account';

    /**
     * @return BillPayments
     * @throws \ErrorException
     */
    public function getBillPayment(): BillPayments
    {
        return new BillPayments(config('services.qiwi.secret'));
    }

    /**
     * @return string
     */
    public function getPublicKey(): string {
        return config('services.qiwi.public');
    }
}
