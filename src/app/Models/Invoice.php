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
    //public const  =

    public const TABLE_NAME = 'invoice';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        self::ID, self::USER_ID, self::STATUS, self::CURRENCY, self::PRICE, self::COMMENT,
    ];

    /**
     * @var string
     */
    protected $table = self::TABLE_NAME;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    final public function user() {
        return $this->belongsTo(User::class);
    }
}
