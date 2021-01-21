<?php

declare (strict_types=1);
namespace App\Model;

/**
 * @property int $id 
 * @property string $name 
 * @property string $sub_name 
 * @property string $content 
 * @property string $author 
 * @property int $category_type 
 * @property int $status 
 * @property string $creator 
 * @property string $last_modifier 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Article extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'article';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'sub_name',
        'hotfile',
        'content',
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
        'category_type' => 'integer',
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * 文章和音频多对多关联.
     *
     * @return \Hyperf\Database\Model\Relations\BelongsToMany
     */
    public function audios()
    {
        return $this->belongsToMany(Audio::class, 'relation_audio_article', 'article_id', 'audio_id');
    }
}