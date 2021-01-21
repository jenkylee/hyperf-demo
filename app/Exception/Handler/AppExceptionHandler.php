<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Exception\Handler;

use App\Traits\TraceLogger;
use App\Traits\JsonResponse;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Psr\Http\Message\ResponseInterface;
use Hyperf\HttpServer\Contract\RequestInterface as HttpRequest;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    use TraceLogger;
    use JsonResponse;

    /**
     * @var integer
     */
    protected $appId;

    /**
     * @var HttpRequest
     */
    protected $request;

    /**
     * @var HttpResponse
     */
    protected $response;

    public function __construct(HttpRequest $request, HttpResponse $response)
    {
        $this->request = $request;
        $this->response = $response;

        $this->appId = config('app.app_id');
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $this->traceLogger()->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
        $this->traceLogger()->error($throwable->getTraceAsString());

        $data = [
            'code' => $this->appId . $throwable->getCode(),
            'message' => $throwable->getMessage(),
        ];

        return $this->response->json($data);
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
