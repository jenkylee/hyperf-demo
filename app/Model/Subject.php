<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\ModelCache\Cacheable;
use Hyperf\ModelCache\CacheableInterface;

/**
 * @property int $id 
 * @property int $parent_id 
 * @property string $name 
 * @property int $depth 
 * @property int $enabled 
 * @property int $sort 
 * @property \Carbon\Carbon $created_at 
 * @property int $subject_id 
 * @property string $prefix 
 */
class Subject extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subject';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'depth',
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
        'parent_id' => 'integer',
        'depth' => 'integer',
        'enabled' => 'integer',
        'sort' => 'integer',
        'created_at' => 'datetime',
        'subject_id' => 'integer'
    ];
}