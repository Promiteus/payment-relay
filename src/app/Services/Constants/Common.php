<?php

namespace App\Services\Constants;

class Common
{
    public const WAITING_STATUS = 'WAITING';
    public const PAID_STATUS = 'PAID';
    public const REJECTED_STATUS = 'REJECTED';
    public const EXPIRED_STATUS = 'EXPIRED';
    public const BILL_ID = 'billId';
    public const PURCHASE_CODES = 'purchaseCodes';
    public const USER_ID = 'userId';

    public const MSG_EMPTY_ORDER_PARAMS = 'There are no order params!';
    public const MSG_EMPTY_BOTH_ORDER_PARAMS = 'Both order params are empty!';
}
