<?php

declare (strict_types=1);
namespace App\Model;

/**
 * @property int $id 
 * @property int $article_id 
 * @property int $audio_id 
 * @property int $sort 
 * @property string $creator 
 * @property string $last_modifier 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class RelationAudioArticle extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'relation_audio_article';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'article_id' => 'integer',
        'audio_id' => 'integer',
        'sort' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}