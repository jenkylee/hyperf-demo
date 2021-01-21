<?php

declare(strict_types=1);

namespace App\Middleware;

use Hyperf\Utils\Context;
use Hyperf\HttpServer\Contract\RequestInterface as HttpRequest;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Hyperf\Snowflake\IdGeneratorInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LogMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var 请求对象
     */
    protected $request;

    /**
     * @var 响应对象
     */
    protected $response;

    public function __construct(ContainerInterface $container, HttpRequest $request, HttpResponse $response)
    {
        $this->container = $container;
        $this->request   = $request;
        $this->response  = $response;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $host = $request->getHeaderLine('host');
        $isLocalHost = false;
        if (strstr($host, 'localhost') !== false) {
            $isLocalHost = true;
        }

        $log_enable = config('app.app_log_enable');
        if ($log_enable && !$isLocalHost) { // 请求及响应日志总开关
            $trace_id = $this->request->post('trace_id');
            if (empty($trace_id)) {
                $generator = $this->container->get(IdGeneratorInterface::class);
                $trace_id = (string)$generator->generate();
            }

            // 将新的请求对象设置到上下文中
            Context::set('traceId', $trace_id);

            $method = $request->getMethod();
            $uri    = $request->getUri();
            $name = "[TRACE:$trace_id] app";

            $request_log_enable = config('app.request_log_enable');
            if ($request_log_enable) { // 请求日志开关
                $reqHeaders = json_encode($request->getHeaders(), JSON_UNESCAPED_UNICODE);
                $reqBody = $request->getBody();
                // 请求日志
                co(function () use ($name, $method, $uri, $reqHeaders, $reqBody) {
                    $message = sprintf("%s:[uri]%s[请求头]%s[请求内容]%s", $method, $uri, $reqHeaders, $reqBody);
                    logger($name)->info($message);
                });
            }

            $response = $handler->handle($request);
            $response_log_enable = config('app.response_log_enable');
            if ($response_log_enable) { // 响应日志开关
                $resHeaders = json_encode($response->getHeaders(), JSON_UNESCAPED_UNICODE);
                $resBody    = $response->getBody();
                // 响应日志
                co(function () use ($name, $method, $uri, $resHeaders, $resBody) {
                    $message = sprintf("%s:[uri]%s[响应头]%s[响应内容]%s", $method, $uri, $resHeaders, $resBody);
                    logger($name)->info($message);
                });
            }

            return $response;
        }

        return $handler->handle($request);
    }
}