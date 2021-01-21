<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Constants\ErrorCode;
use App\Service\AuthService;
use App\Traits\JsonResponse;
use App\Traits\TraceLogger;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\HttpServer\Contract\RequestInterface as HttpRequest;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;

class AuthMiddleware implements MiddlewareInterface
{
    use TraceLogger;
    use JsonResponse;

    /**
     * @var integer
     */
    protected $appId;

    /**
     * 内网IP
     *
     * @var string
     */
    protected $internalIp;

    /**
     * @var bool
     */
    protected $authEnable;

    /**
     * @var HttpRequest
     */
    protected $request;

    /**
     * @var HttpResponse
     */
    protected $response;

    /**
     * @Inject()
     *
     * @var AuthService
     */
    protected $authService;

    public function __construct(HttpRequest $request, HttpResponse $response)
    {
        $this->request = $request;
        $this->response = $response;

        $this->appId = config('app.app_id');
        $this->authEnable = config('app.auth_enable');
        $this->internalIp = config('app.internal_ip');
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->authEnable) {
            $notInternalAccess = true;
            if (!empty($this->internalIp)) {
                $host = $request->getHeaderLine('host');
                if (strstr($host, $this->internalIp) !== false) {
                    $notInternalAccess = false;
                }
            }
            if ($notInternalAccess) { // 内部网络访问，禁止鉴权
                $token = $request->getHeaderLine('Authorization');
                if (!$this->authService->checkToken($token)) {
                    $data = [
                        'code' => $this->appId . ErrorCode::PARAM_ERROR,
                        'message' => '访问未授权',
                    ];

                    $this->traceLogger()->info(json_encode($data, JSON_UNESCAPED_UNICODE));
                    return $this->response->json($data);
                }
            }
        }
        return $handler->handle($request);
    }
}