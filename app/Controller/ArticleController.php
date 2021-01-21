<?php

declare(strict_types=1);

namespace App\Controller;

use App\Constants\CacheConst;
use App\Constants\ErrorCode;
use App\Request\ArticleRequest;
use App\Traits\JsonResponse;
use App\Traits\TraceLogger;
use Hyperf\Di\Annotation\Inject;
use App\Service\ArticleService;

class ArticleController extends AbstractController
{
    use JsonResponse;
    use TraceLogger;

    /**
     * @Inject()
     * @var ArticleService
     */
    private $ArticleService;

    /**
     * 文章详情
     *
     * @param \App\Request\ArticleRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function details(ArticleRequest $request)
    {
        $validated = $request->validated();
        $id = (int)$validated['id'];

        $key = CacheConst::ARTICLE_DETAIL_PREFIX . validated_cache_key($validated);
        $data = cache()->get($key);
        if (empty($data)) {
            $data = $this->ArticleService->getArticleDetail($id);
            go(function () use($key, $data) {
                cache()->set($key, json_encode($data), CacheConst::CACHE_TTL_HOUR);
                cache_tag(CacheConst::ARTICLE_DETAIL_PREFIX, $key);
            });
        } else {
            $data = json_decode($data);
        }
        unset($validated);

        return $this->json([
            'code' => ErrorCode::SUCCESS,
            'message' => ErrorCode::getMessage(ErrorCode::SUCCESS),
            'data' => $data
        ]);
    }

    /**
     * 音频详情
     *
     * @param \App\Request\ArticleRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function audioDetails(ArticleRequest $request)
    {
        $validated = $request->validated();
        $id = (int)$validated['id'];
        $data = $this->ArticleService->getAudioDetail($id);

        unset($validated);
        
        return $this->json([
            'code' => ErrorCode::SUCCESS,
            'message' => ErrorCode::getMessage(ErrorCode::SUCCESS),
            'data' => $data
        ]);
    }
}
