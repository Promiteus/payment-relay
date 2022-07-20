<?php

namespace App\dto;

use App\Models\Invoice;
use App\Services\Constants\Common;

class BillStatusResponse
{
    /**
     * @var string
     */
    private string $status;
    /**
     * @var string
     */
    private string $billId;
    /**
     * @var string
     */
    private string $payUrl;

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getBillId(): string
    {
        return $this->billId;
    }

    /**
     * @return string
     */
    public function getPayUrl(): string
    {
        return $this->payUrl;
    }

    public function __construct()
    {
        $this->payUrl = '';
        $this->status = '';
        $this->billId = '';
    }


    final public function fromBodySet(array $body): BillStatusResponse {
        $this->payUrl = array_key_exists(Common::PAY_URL, $body) ? $body[Common::PAY_URL] : $this->payUrl;
        $this->status = array_key_exists(Invoice::STATUS, $body) && (is_array($body[Invoice::STATUS]) && $body[Invoice::STATUS][Common::VALUE]) ? $body[Invoice::STATUS][Common::VALUE] : $this->status;
        $this->billId = array_key_exists(Common::BILL_ID, $body) ? $body[Common::BILL_ID] : $this->billId;

        return $this;
    }

    final public function toArray() {
        return [
            Invoice::STATUS => $this->status,
            Common::BILL_ID => $this->billId,
            Common::PAY_URL => $this->payUrl,
        ];
    }
}
