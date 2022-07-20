<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    public const PAY_URL = 'pay_url';


    public const TABLE_NAME = 'invoices';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        self::ID,
        self::USER_ID,
        self::STATUS,
        self::CURRENCY,
        self::PRICE,
        self::COMMENT,
        self::EXPIRATION_DATETIME,
        self::PAY_URL,
    ];

    /**
     * @var string
     */
    protected $table = self::TABLE_NAME;

    protected $keyType = 'string';

    /**
     * @return BelongsTo
     */
    final public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsToMany
     */
    final public function products():BelongsToMany {
        return $this->belongsToMany(Product::class);
    }


    /**
     * @return HasMany
     */
    final public function productInvoices(): HasMany {
        return $this->hasMany(ProductInvoice::class);
    }
}
