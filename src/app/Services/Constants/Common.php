<?php

namespace App\Services\Constants;

class Common
{
    public const WAITING_STATUS = 'WAITING';
    public const PAID_STATUS = 'PAID';
    public const REJECTED_STATUS = 'REJECTED';
    public const EXPIRED_STATUS = 'EXPIRED';
    public const BILL_ID = 'billId';
    public const PRODUCTS = 'products';
    public const CODE = 'code';
    public const COUNT = 'count';
    public const ITEMS = 'items';
    public const USER_ID = 'userId';

    public const MSG_EMPTY_ORDER_PARAMS = 'There are no order params!';
    public const MSG_EMPTY_BOTH_ORDER_PARAMS = 'Both order params are empty!';
    public const MSG_CANT_UPDATE_INVOICE_STATUS = 'Can\'t update invoice status!';
    public const MSG_CANT_GET_INVOICE_STATUS_FROM_SERVER = 'Can\'t get invoice status from payment service!';
    public const MSG_NOT_ALL_PARAMETERS_FOR_METHOD = 'Method \'%s\' have got not all params!';
}
