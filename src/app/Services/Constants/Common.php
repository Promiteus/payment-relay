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
    public const PAY_URL = 'payUrl';
    public const VALUE = 'value';
    public const AMOUNT = 'amount';
    public const CURRENCY = 'currency';
    public const COMMENT = 'comment';
    public const EXPIRATION_DATE = 'expirationDateTime';
    public const EMAIL = 'email';
    public const ACCOUNT = 'account';
    public const TOTAL_PRICE = 'totalPrice';

    public const MSG_EMPTY_BILL_ID = 'BillId is empty!';

    public const MSG_EMPTY_ORDER_PARAMS = 'There are no order params!';
    public const MSG_EMPTY_BOTH_ORDER_PARAMS = 'Both order params are empty!';
    public const MSG_CANT_UPDATE_INVOICE_STATUS = 'Can\'t update invoice status!';
    public const MSG_CANT_GET_INVOICE_STATUS_FROM_SERVER = 'Can\'t get invoice status from payment service!';
    public const MSG_NOT_ALL_PARAMETERS_FOR_METHOD = 'Method \'%s\' have got not all params!';
    public const MSG_CANT_CREATE_INVOICE = 'Can\'t create invoice!';
    public const MSG_EMPTY_PRODUCTS = 'Product list is empty!';
    public const MSG_PRODUCTS_WITH_SUCH_CODES_NOT_FOUND = 'Products with such codes have not found!!';
}
