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
use Hyperf\Nacos\Constants;

return [
    'enable' => env('NACOS_ENABLE', true), // 是否启用服务注册
    // The nacos host info
    'host' => env('NACOS_HOST', '127.0.0.1'),
    'port' => env('NACOS_PORT', 8848),
    // The nacos account info
    'username' => env('NACOS_USERNAME', null),
    'password' => env('NACOS_PASSWORD', null),
    'config_merge_mode' => Constants::CONFIG_MERGE_OVERWRITE,
    // 服务注册，新服务会自动创建后再注册实例.
    'service' => [
        'service_name' => env('SERVICE_NAME', 'hyperf'),
        'group_name' => 'api', // 服务组
        'namespace_id' => env('NAMESPACE_ID', 'namespace_id'),  // 命名空间ID
        'protect_threshold' => 0.5,
    ],
    // The client info.
    'client' => [
        'service_name' => env('SERVICE_NAME', 'hyperf'),
        'group_name' => 'api',
        'weight' => 80,
        'cluster' => 'DEFAULT',
        'ephemeral' => true,
        'beat_enable' => true, // 是否发送实例心跳
        'beat_interval' => 5, // 心跳周期
        'namespace_id' => env('NAMESPACE_ID', 'namespace_id'), // It must be equal with service.namespaceId.
    ],
    'remove_node_when_server_shutdown' => true, // 关机是否注销实例
    'config_reload_interval' => 3,
    'config_append_node' => env('CONFIG_APPEND_NODE', 'nacos_config'),
    'listener_config' => [
        // dataId, group, tenant, type, content
        //[
        //    'tenant' => 'tenant', // corresponding with service.namespaceId
        //    'data_id' => 'hyperf-service-config',
        //    'group' => 'DEFAULT_GROUP',
        //],
        [
            'tenant' => env('NAMESPACE_ID', 'namespace_id'), // corresponding with service.namespaceId
            'data_id' => env('NACOS_DATA_ID', 'hyperf-service-config-yml'),
            'group' => 'DEFAULT_GROUP',
            'type' => env('NACOS_DATA_TYPE', 'yml'),
        ],
    ],
    'load_balancer' => 'random',
];
