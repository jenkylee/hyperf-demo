<?php

namespace App\Exception\Handler;

use App\Traits\JsonResponse;
use App\Traits\TraceLogger;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Hyperf\HttpServer\Contract\RequestInterface as HttpRequest;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Throwable;

class ValidationExceptionHandler extends ExceptionHandler
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
        $this->stopPropagation();
        /** @var \Hyperf\Validation\ValidationException $throwable */
        $message = $throwable->validator->errors()->first();
        // 传入异常是希望捕获的 ApiException， 格式化为json格式输出
        $data = [
            'code' => $this->appId . $throwable->status,
            'message' => $message,
        ];

        $this->traceLogger()->info(json_encode($throwable->errors(), JSON_UNESCAPED_UNICODE));
        return $this->response->json($data);
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof ValidationException;
    }
}