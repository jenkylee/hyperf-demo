<?php

declare(strict_types = 1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller;

use App\Constants\ErrorCode;
use App\Model\User;
use App\Traits\JsonResponse;
use Qbhy\HyperfAuth\AuthManager;
use Hyperf\Di\Annotation\Inject;

class IndexController extends AbstractController
{
    use JsonResponse;
    /**
     * @Inject()
     * @var AuthManager
     */
    protected $auth;

    public function saiindex()
    {
        return 'ok';
    }

    public function index()
    {
        //cache()->set('test:2456', 'Hello World.');
        //cache()->delete('test:2456');

        return $this->json(
            [
                'code' => ErrorCode::SUCCESS,
                'message' => ErrorCode::getMessage(ErrorCode::SUCCESS),
            ]
        );
    }

    public function auth()
    {
        $user   = $this->request->input('user', 'admin');
        $password = $this->request->input('passowrd', 'dtt123');
        $method = $this->request->getMethod();
        $data = [
            'identify' => $user,
            'password' => $password
        ];
        $user = new User($data);
        $token = $this->auth->login($user);
        $this->traceLogger()->info("test");
        $this->traceLogger()->error('error');

        return [
            'method'  => $method,
            'message' => "Hello world.",
            'token' => $token
        ];
    }
}
