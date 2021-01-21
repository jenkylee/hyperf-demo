<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\ModelCache\Cacheable;
use Hyperf\ModelCache\CacheableInterface;

/**
 * @property int $id 
 * @property string $name 
 * @property int $enabled 
 * @property int $sort 
 * @property \Carbon\Carbon $created_at 
 * @property string $prefix 
 */
class Version extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'version';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'enabled',
        'sort',
        'prefix'
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'enabled' => 'integer',
        'sort' => 'integer',
        'created_at' => 'datetime'
    ];
}