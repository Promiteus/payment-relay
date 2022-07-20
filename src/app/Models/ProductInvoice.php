<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ProductInvoice
 * @package App\Models
 */
class ProductInvoice extends Model
{
    public const ID = 'id';
    public const TABLE_NAME = 'invoice_product';
    public const PRODUCT_ID = 'product_id';
    public const INVOICE_ID = 'invoice_id';
    public const EXPIRED_OPT_AT = 'expired_opt_at'; //Дата и время истечения услуги/опции. Для товаров null.

    protected $table = self::TABLE_NAME;

    protected $fillable = [
        self::ID,
        self::INVOICE_ID,
        self::PRODUCT_ID,
        self::EXPIRED_OPT_AT,
    ];

    protected $keyType = 'string';

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsTo
     */
    public function invoice(): BelongsTo {
        return $this->belongsTo(Invoice::class);
    }
}
