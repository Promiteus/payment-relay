<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Invoice
 * @package App\Models
 */
class Invoice extends Model
{
    public const ID = 'id';
    public const USER_ID = 'user_id';
    public const STATUS = 'status';
    public const CURRENCY = 'currency';
    public const PRICE = 'price';
    public const COMMENT = 'comment';
    public const EXPIRATION_DATETIME = 'expired_at'; //срок истечения выставленного счета


    public const TABLE_NAME = 'invoices';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        self::ID, self::USER_ID, self::STATUS, self::CURRENCY, self::PRICE, self::COMMENT, self::EXPIRATION_DATETIME,
    ];

    /**
     * @var string
     */
    protected $table = self::TABLE_NAME;

    protected $keyType = 'string';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    final public function user() {
        return $this->belongsTo(User::class);
    }


    final public function products() {
        return $this->belongsToMany(Product::class);
    }



    final public function productInvoices() {
        return $this->hasMany(ProductInvoice::class);
    }
}
