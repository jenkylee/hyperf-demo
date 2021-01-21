<?php

declare(strict_types=1);

namespace App\Service;

class AuthService extends Service
{
    /**
     * @var
     */
    private $tokens;

    public function __construct()
    {
        $this->tokens = collect(config('const.tokens'));
    }

    /**
     * 检测授权
     *
     * @param string $token
     * @return bool
     */
    public function checkToken( string $token)
    {
        $auth = $this->getAuthInfo($token);
        if ($auth) {
            return true;
        }
        return false;
    }

    /**
     * 获取授权信息
     *
     * @param string $token
     * @return array
     */
    public function getAuthInfo(string $token)
    {
        $env = env('APP_ENV');
        $envs = [$env, 'all'];
        $auth = $this->tokens->where('token', $token)
            ->whereIn('env', $envs)
            ->first();
        return $auth;
    }

    /**
     * 获取授权app
     *
     * @param string $token
     * @return mixed
     */
    public function getAuthApp(string $token)
    {
        $auth = $this->getAuthInfo($token);

        return $auth['app'];
    }
}