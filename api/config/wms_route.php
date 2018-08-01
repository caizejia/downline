<?php
// WMS 路由规则配置
return [
    [
        'class' => 'yii\rest\UrlRule', 
        'pluralize' => false, 
        'controller' => [
            'v1/warehouse', 
            'v1/pick-round-detail', 
            'v1/pick-round', 
            'v1/so-bill', 
            'v1/sub-inventory-log', 
            'v1/problems', 
            'v1/pr-bill', 
            'v1/receivables', 
            'v1/wms2-uploads', 
            'v1/inventory-detail', 
            //'v1/wms-ws-bill',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/article',
        'ruleConfig' => [
            'class' => 'yii\web\UrlRule',
            'defaults' => [
                'expand' => 'createdBy',
            ],
        ],
        'extraPatterns' => [
            'POST search' => 'search',
        ],
        'tokens' => [
        '{id}' => '<id:\\w+>',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/top10',
        'except' => ['delete', 'create', 'update', 'view'],
        'pluralize' => false, //代表去掉restful中默认使用的复数形式（s）;
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/inventory',
        'except' => ['delete', 'create', 'update'],
        'pluralize' => false, //代表去掉restful中默认使用的复数形式（s）;
        'extraPatterns' => [
            'POST search' => 'search',
            'GET mock-lock' => 'mocklock',
            'GET mock-purchase' => 'mockpurchase',
            'GET mock-stock' => 'mockstock',
            
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/wms-uploads',
        'except' => ['delete', 'create', 'update'],
        'pluralize' => false, //代表去掉restful中默认使用的复数形式（s）;
        'extraPatterns' => [
            'POST upload' => 'upload',
        ],
    ],
    //[
    //    'class' => 'yii\rest\UrlRule',
    //    'controller' => 'v1/adminuser',
    //    'except' => ['delete', 'create', 'update', 'view'],
    //    'pluralize' => false,
    //    'extraPatterns' => [
    //        'POST login' => 'login',
    //    ],
    //],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/purchases',
        'except' => ['delete'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST search' => 'search',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/purchases-detail',
        'except' => ['delete'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST search' => 'search',
            'POST update-purchases-count' => 'update-purchases-count',
            'POST confirm' => 'confirm',
            'POST payment-request' => 'payment-request',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/sub-inventory',
        'pluralize' => false, //代表去掉restful中默认使用的复数形式（s）;
        'extraPatterns' => [
            'POST search' => 'search',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/sub-inventory-sku',
        'except' => ['delete', 'create', 'update'],
        'pluralize' => false, //代表去掉restful中默认使用的复数形式（s）;
        'extraPatterns' => [
            'POST scan' => 'scan',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/payments',
        'except' => ['create', 'view'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST search' => 'search',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/pw-bill',
        'except' => ['delete'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST search' => 'search',
            'POST returned-purchase' => 'returned-purchase',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/stocks',
        'pluralize' => false, //代表去掉restful中默认使用的复数形式（s）;
        'extraPatterns' => [
            'POST test' => 'test',
            'GET stock' => 'stock',
            'GET index' => 'index',
            'GET outbound' => 'outbound',
            'GET outbound-list' => 'outbound-list',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/so-bill',
        'pluralize' => false, //代表去掉restful中默认使用的复数形式（s）;
        'extraPatterns' => [
            'POST check' => 'check',
            'POST size' => 'size',
            'POST weight' => 'weight',
            'POST out' => 'out',
            'POST pickprintjhd' => 'pickprintjhd',
        ],
    ],

    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/wms-ws-bill',
        'pluralize' => false, //代表去掉restful中默认使用的复数形式（s）;
        'extraPatterns' => [
            'GET alist' => 'alist',
            'GET blist' => 'blist',
        ],
    ],
 
];
