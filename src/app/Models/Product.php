<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
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

    protected $table = self::TABLE_NAME;

    protected $fillable = [
        self::PRICE,
        self::NAME,
        self::CODE,
        self::DESCRIPTION,
        self::ID
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    final public function invoices() {
        return $this->belongsToMany(Invoice::class);
    }


}
