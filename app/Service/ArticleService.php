<?php

namespace App\Service;

use App\Model\Article;
use App\Model\Audio;
use Hyperf\Di\Annotation\Inject;

class ArticleService extends Service
{
    /**
     * @Inject()
     * @var Article
     */
    private $model;

    /**
     * 根据文章ID获取以文章ID为键的文章及文音列表
     *
     * @param array $ids
     * @return array
     */
    public function getArticlesKeyById(array $ids)
    {
        if (empty($ids)) {
            return [];
        }

        $articles = $this->model->query()
            ->select([
                'id',
                'name',
                'sub_name',
                'hotfile',
                'content',
                'author'
            ])
            ->whereIn('id', $ids)
            ->with(['audios' => function ($query) {
                $query->select(['name', 'file_url', 'duration']);
            }])
            ->get()->map(function($item) {
                if (is_object($item->audios)) {
                    foreach($item->audios as &$audio) {
                        $audio->id = $audio->pivot->audio_id;
                        unset($audio->pivot);
                    }
                    return $item;
                }
            })
            ->keyBy('id')
            ->toArray();

        return $articles;
    }

    /**
     * 根据文章ID获取以文章详情
     *
     * @param int $id
     * @return array
     */
    public function getArticleDetail(int $id)
    {
        $this->traceLogger()->info("跟踪测试");
        $data = $this->model->query()
            ->select('id', 'name', 'sub_name', 'hotfile', 'content', 'author')
            ->with(['audios' => function($query) {
                $query->select(['name', 'file_url', 'duration']);
            }])
            ->find($id);
        if ($data){
            if (is_object($data->audios)) {
                $data->audios->map(function($item) {
                    if ($item){
                        $item->id = $item->pivot->audio_id;
                        unset($item->pivot);
                        return $item;
                    }
                });
            }
        }
        return $data;
    }

    /**
     * 根据音频ID获取以音频详情
     *
     * @param int $id
     * @return array
     */
    public function getAudioDetail(int $id)
    {
        $data = Audio::query()
            ->select('id', 'name', 'file_url', 'duration', 'author')
            ->find($id);

        return $data;
    }
}