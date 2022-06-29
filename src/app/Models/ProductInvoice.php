<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductInvoice
 * @package App\Models
 */
class ProductInvoice extends Model
{
    public const TABLE_NAME = 'product_invoice';
    public const PRODUCT_ID = 'product_id';
    public const INVOICE_ID = 'invoice_id';
    public const ID = 'id';

    protected $table = self::TABLE_NAME;

    protected $fillable = [
        self::INVOICE_ID,
        self::PRODUCT_ID,
        self::ID
    ];

    protected $keyType = 'string';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product() {
        return $this->belongsTo(Product::class, self::PRODUCT_ID);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice() {
        return $this->belongsTo(Invoice::class, self::INVOICE_ID);
    }
}
