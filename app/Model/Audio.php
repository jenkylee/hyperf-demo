<?php

declare (strict_types=1);
namespace App\Model;

/**
 * @property int $id 
 * @property string $name 
 * @property string $file_url 
 * @property int $duration 
 * @property string $author 
 * @property int $category_type 
 * @property int $status 
 * @property string $creator 
 * @property string $last_modifier 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Audio extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'audio';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'file_url',
        'duration',
        'author',
        'category_type',
        'status',
        'creator',
        'last_modifier',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'duration' => 'integer',
        'category_type' => 'integer',
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}