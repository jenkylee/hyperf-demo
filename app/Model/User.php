<?php

declare (strict_types=1);
namespace App\Model;

use Qbhy\HyperfAuth\Authenticatable;
/**
 * @property int $id 
 * @property string $identify 
 * @property string $password 
 * @property \Carbon\Carbon $created_at 
 * @property string $deleted_at 
 * @property string $role 
 * @property string $mobile 
 * @property int $login_type 
 * @property int $enabled 
 * @property string $real_name 
 * @property string $pwdmd5 
 */
class User extends Model implements Authenticatable
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'identify',
        'password',
        'mobile'
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'created_at' => 'datetime',
        'login_type' => 'integer',
        'enabled' => 'integer'
    ];

    public function getId()
    {
    }

    public static function retrieveById($key): ?Authenticatable
    {
    }
}