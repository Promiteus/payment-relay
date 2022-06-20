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

    public const MSG_EMPTY_BILL_ID = 'BillId is empty!';

    protected BillPayments $billPayments;

    /**
     * BillService constructor.
     * @param BillPayments $billPayments
     * @throws \ErrorException
     */
    public function __construct(BillPayments $billPayments)
    {
        $this->billPayments = new BillPayments(config('services.qiwi.secret'));
    }

    /**
     * @return BillPayments
     */
    public function getBillPayment(): BillPayments
    {
        return $this->billPayments;
    }

    /**
     * @return string
     */
    public function getPublicKey(): string {
        return config('services.qiwi.public');
    }
}
