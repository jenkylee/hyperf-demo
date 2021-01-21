<?php

namespace App\Exception\Handler;

use App\Exception\ApiException;
use App\Traits\TraceLogger;
use App\Traits\JsonResponse;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Psr\Http\Message\ResponseInterface;
use Hyperf\HttpServer\Contract\RequestInterface as HttpRequest;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Throwable;

class ApiExceptionHandler extends ExceptionHandler
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
        // 判断传入的异常是否是当前处理器处理的异常
        if ($throwable instanceof ApiException) {

            $this->traceLogger()->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
            $this->traceLogger()->error($throwable->getTraceAsString());

            // 传入异常是希望捕获的 ApiException， 格式化为json格式输出
            $data = [
               'code' => $this->appId . $throwable->getCode(),
               'message' => $throwable->getMessage(),
            ];
            return $this->response->json($data);
        }
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}