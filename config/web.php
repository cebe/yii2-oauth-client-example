<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name' => 'OAuth JWT Client',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', \Da\User\Bootstrap::class],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],

    'modules' => [

        'user' => require __DIR__ . '/usuario.php',

    ],

    'components' => [
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'github' => [ // github added to learn and understand our own client
                    'class' => \Da\User\AuthClient\GitHub::class,
                    'clientId' => '',
                    'clientSecret' => ''
                ],
                // 'oauthserver' => [
                //     'class' => \app\components\OauthServerDaClient::class,
                //     'clientId' => 1,
                //     'clientSecret' => 'secrets65df65sd65f4s6d45f',
                // ],
                'oauthserver' => [
                    'class' => \app\components\OauthServerDaClient::class,
                    'clientId' => 1, // replace this
                    'clientSecret' => 'RD2LHVDuN9GmqoOD8kGD9NFTnyVYhlRLM1Vee-gnedasff81e4_ay7c0nQZ1xCKI', // replace this
                ],
            ]
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'P64Ah2ZgFceUSwDiE4lN-1unDJYnAVTg',
        ],
        'session' => [
            'class' => \yii\web\Session::class,
            'name' => 'oauth_client'
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => \yii\rbac\DbManager::class,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
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
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
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
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
