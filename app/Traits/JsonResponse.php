<?php
declare(strict_types=1);

namespace App\Traits;

use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
trait JsonResponse
{
    public function json($data) : PsrResponseInterface
    {
        $appId = config('app.app_id');

        $data['code'] = $appId . $data['code'];

        return $this->response->json($data);
    }
}