<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * @property string $id
 * @package App\Models
 */

class Product extends Model
{
    public const TABLE_NAME = 'products';

    public const ID = 'id';
    public const PRICE = 'price';
    public const NAME = 'name';
    public const CODE = 'code';
    public const DESCRIPTION = 'description';
    public const DISABLED = 'disabled';
    public const CATEGORY_ID = 'category_id';
    public const EXPIRATION_DAYS = 'expiration_days'; //Срок истечения услуги/опции. Для товаров null.

    protected $table = self::TABLE_NAME;

    protected $keyType = 'string';

    protected $fillable = [
        self::PRICE,
        self::NAME,
        self::CODE,
        self::DESCRIPTION,
        self::ID,
        self::EXPIRATION_DAYS,
    ];

    protected $hidden = [
        self::CREATED_AT,
        self::UPDATED_AT
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    final public function invoices() {
        return $this->belongsToMany(Invoice::class);
    }


}
