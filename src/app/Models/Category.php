<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 * @package App\Models
 * @property string $id
 * @property string $name
 * @property boolean $disabled
 */
class Category extends Model
{
   public const TABLE_NAME = 'categories';
   public const ID = 'id';
   public const NAME = 'name';
   public const DISABLED = 'disabled';

   protected $keyType = 'string';

   protected $table = self::TABLE_NAME;
}
