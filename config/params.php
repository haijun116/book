<?php

return [
    'title' => '图书商城1',
    'desc' => '图书商城描述',
    'adminEmail' => 'admin@example.com',
    'domain' => [
        'www' => 'http://book.cn/',
        'm' => 'http://book.cn/m',
        'web' => 'http://book.cn/web',
        'blog' => "http://blog.dr.test",
//        'www' => 'http://sky116.easy.echosite.cn/',
//        'm' => 'http://sky116.easy.echosite.cn/m',
//        'web' => 'http://sky116.easy.echosite.cn/web',
//        'blog' => "http://sky116.easy.echosite.cn/"
    ],
    'upload' => [
        'avatar' => '/uploads/avatar',
        'book' => '/uploads/book',
        'brand' => '/uploads/brand'
    ],
    'weixin' => [
        'appid' => 'wx650f8b1af40e1da6',
        'sk' => '719d400bc1801786d04d98051c3fa778',
        'token' => 'weixin',
        'pay' => [
            'key' => '',
            'mch_id' => '',
            'notify_url' => [
                'm' => '/pay/callback'
            ]
        ]
    ]
];
