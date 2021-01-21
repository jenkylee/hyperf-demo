<?php

declare(strict_types=1);

namespace App\Controller;

use App\Constants\ErrorCode;
use App\Traits\JsonResponse;
use App\Service\ClearCacheService;
use Hyperf\Di\Annotation\Inject;

class ClearController extends AbstractController
{
    use JsonResponse;

    /**
     * @Inject()
     * @var ClearCacheService
     */
    private $clearCacheService;

    public function index()
    {
        $tag = $this->request->post('tag', '');
        $key = $this->request->post('key', '');
        $paper_id = $this->request->post('paper_id', 0);
        $bool = true;
        if ($tag) {
            $bool = clear_cache_tag($tag);
        }
        if ($key) {
            $bool = clear_cache_key($key);
        }
        if ($paper_id > 0) {
            $this->clearCacheService->flushPaperCache($paper_id);
        }

        return $this->json([
            'code' => ErrorCode::SUCCESS,
            'message' => ErrorCode::getMessage(ErrorCode::SUCCESS),
            'data' => $bool
        ]);
    }
}
