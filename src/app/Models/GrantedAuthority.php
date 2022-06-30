<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GrantedAuthority
 * @package App\Models
 */
class GrantedAuthority extends Model
{
    public const TABLE_NAME = 'granted_authority';
    public const USER_ID = 'user_id';
    public const ROLE = 'role';
    public const ID = 'id';

    protected $table = self::TABLE_NAME;


}
