<?php


namespace App\dto;


use App\Models\Invoice;
use App\Services\Constants\Common;

class InvoiceBody
{
    /**
     * @var string
     */
    private string $billId;
    /**
     * @var float
     */
    private float $amount;
    /**
     * @var string
     */
    private string $comment;
    /**
     * @var string
     */
    private string $currency;
    /**
     * @var string
     */
    private string $email;
    /**
     * @var string
     */
    private string $status;
    /**
     * @var string
     */
    private string $expirationDays;

    public function __construct(string $expirationDays)
    {
        $this->status = '';
        $this->email = '';
        $this->currency = 'RUB';
        $this->comment = '';
        $this->amount = 0;
        $this->billId = '';
        $this->expirationDays = $expirationDays;
    }

    /**
     * @return string
     */
    public function getBillId(): string
    {
        return $this->billId;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @return string
     */
    public function getExpirationDays(): string
    {
        return $this->expirationDays;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /*
     *  $invoice = [
            Common::BILL_ID => $billId,
            Common::AMOUNT => [
                Common::CURRENCY => 'RUB',
                Common::VALUE => $totalPrice,
            ],
            Invoice::STATUS => [
                Common::VALUE => Common::WAITING_STATUS,
            ],
            Common::COMMENT => 'any comment',
        ];
     * */

    public function fromBodySet(array $body) {
        $this->comment = $body[Common::COMMENT] ?: $this->comment;
        $this->email = $body[Common::EMAIL] ?: $this->email;
        $this->billId = $body[Common::BILL_ID] ?: $this->billId;
        $this->amount = $body[Common::AMOUNT] ?: $this->amount;
        $this->status = $body[Invoice::STATUS] ?: $this->status;
    }

    /**
     * @return array
     */
    final public function toArray(): array {
        return [
            Common::BILL_ID => $this->billId,
            Common::AMOUNT => $this->amount,
            Common::CURRENCY => $this->currency,
            Common::COMMENT => $this->comment,
            Common::EXPIRATION_DATE => $this->expirationDays,
            Common::EMAIL => $this->email,
        ];
    }
}
