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
use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/saiindex', 'App\Controller\IndexController@saiindex');
Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');
Router::addRoute(['GET', 'POST', 'HEAD'], '/auth', 'App\Controller\IndexController@auth');

Router::get('/favicon.ico', function () {
    return '';
});

// v1组所有路由
Router::addGroup('/v1', function () {
    //文章详情
    Router::post('/article/query-content', [\App\Controller\ArticleController::class, 'details']);
    //音频详情
    Router::post('/audio/query-content', [\App\Controller\ArticleController::class, 'audioDetails']);

    Router::post('/cache/clear', [\App\Controller\ClearController::class, 'index']);
}, ['middleware' => [\App\Middleware\AuthMiddleware::class]]);

