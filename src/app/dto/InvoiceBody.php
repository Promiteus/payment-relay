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
     private string $status;
     /**
      * @var string
      */
     private string $expirationDays;

     private static $instance = null;

     private function __construct(string $expirationDays = '')
     {
         $this->expirationDays = $expirationDays;
     }

     private function init() {
         $this->status = '';
         $this->currency = 'RUB';
         $this->comment = '';
         $this->amount = 0;
         $this->billId = '';
     }

     public static function getInstance(string $expirationDays = ''): InvoiceBody {
         if (is_null(self::$instance)) {
             self::$instance = new InvoiceBody($expirationDays);
         }

         return self::$instance;
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

    public function fromBodySet(array $body): InvoiceBody {
        $this->init();

        $this->comment = array_key_exists(Common::COMMENT, $body) ? $body[Common::COMMENT] : $this->comment;
        $this->billId = array_key_exists(Common::BILL_ID, $body) ?  $body[Common::BILL_ID] : $this->billId;
        $this->amount = array_key_exists(Common::AMOUNT, $body) ? $body[Common::AMOUNT][Common::VALUE] : $this->amount;
        $this->status = array_key_exists(Invoice::STATUS, $body) ? $body[Invoice::STATUS][Common::VALUE] : $this->status;
        $this->currency = array_key_exists(Common::AMOUNT, $body) ? $body[Common::AMOUNT][Common::CURRENCY] : $this->currency;

        return $this;
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
        ];
    }
}
