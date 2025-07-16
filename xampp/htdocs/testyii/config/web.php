<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();


$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'timeZone' => 'Asia/Ho_Chi_Minh',
    
    
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'formatter' => [
    'class' => 'yii\i18n\Formatter',
    'timeZone' => 'Asia/Ho_Chi_Minh',
    'defaultTimeZone' => 'Asia/Ho_Chi_Minh',
    'datetimeFormat' => 'php:d/m/Y H:i:s', // 24h format
    'dateFormat' => 'php:d/m/Y',
    'timeFormat' => 'php:H:i:s',
    'currencyCode' => 'VND', // 👈 Thêm dòng này để tránh lỗi và hiển thị tiền tệ
    
    
    
],
'redis' => [
        'class' => 'yii\redis\Connection',
        'hostname' => 'localhost',
        'port' => 6379,
        'database' => 0,
        // 'password' => 'your_password', // Nếu Redis yêu cầu
    ],
'momo' => [
    'class' => 'app\components\MomoService',
],


        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'bJnEXVHi9yNSchWUbTx4efW-TBX-yLPS',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'product/index', // Chuyển hướng URL gốc sang product/index
                'cart' => 'cart/index',
        'cart/add/<id:\d+>' => 'cart/add',
        'cart/remove/<id:\d+>' => 'cart/remove',
        'cart/update-quantity/<id:\d+>' => 'cart/update-quantity',
        'cart/checkout' => 'cart/checkout',
        'cart/clear' => 'cart/clear',
        'cart/thanks' => 'cart/thank-you'
        
            ],
        ],
        
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
