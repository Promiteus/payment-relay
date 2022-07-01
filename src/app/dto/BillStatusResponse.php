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

    public function __construct(
        string $status,
        string $billId,
        string $payUrl
    )
    {
        $this->payUrl = $payUrl;
        $this->status = $status;
        $this->billId = $billId;
    }

    final public function toArray() {
        return [
            Invoice::STATUS => $this->status,
            Common::BILL_ID => $this->billId,
            Common::PAY_URL => $this->payUrl,
        ];
    }
}
