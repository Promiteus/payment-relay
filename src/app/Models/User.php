<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 * @package App\Models
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const TABLE_NAME = 'users';

    public const DISABLES = 'disabled';
    public const ID = 'id';
    public const EMAIL = 'email';
    public const PASSWORD = 'password';
    public const REG_DATE = 'reg_date';
    public const USER_NAME = 'user_name';
    public const VERIFIED_EMAIL = 'verified_email';
    public const CODE = 'code';
    public const REMEMBER_TOKEN = 'remember_token';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        self::ID,
        self::DISABLES,
        self::EMAIL,
        self::PASSWORD,
        self::REG_DATE,
        self::USER_NAME,
        self::VERIFIED_EMAIL,
        self::CODE
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        self::PASSWORD,
        self::REMEMBER_TOKEN,
    ];

    protected $table = self::TABLE_NAME;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    final public function invoices() {
        return $this->hasMany(Invoice::class);
    }
}
