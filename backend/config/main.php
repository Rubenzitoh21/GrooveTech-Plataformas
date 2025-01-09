<?php

use yii\log\FileTarget;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'api' => ['class' => 'backend\modules\api\ModuleAPI',
        ],
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            'name' => 'advanced-backend',
            'cookieParams' => ['httpOnly' => true],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning', 'info'],
                    'logVars' => [],
                    'categories' => ['debug'],
                    'logFile' => '@runtime/logs/app.log',
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                //USER PROFILE
                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'api/user-profile',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET profile/{id}' => 'get-profile',
                        'PATCH update/{id}' => 'update-profile',
                    ]
                ],
                //USER
                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'api/user',
                    'pluralize' => false,],
                //AUTH
                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'api/auth',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'POST login' => 'login',
                        'POST signup' => 'signup',
                    ],
                ],
                //PRODUTOS
                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'api/produto',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET all' => 'allproducts',
                        'GET search/{query}' => 'search',
                    ],
                ],
                //CARRINHOS
                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'api/carrinho',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'POST create' => 'create-cart',
                        'GET cart/{id}' => 'get-cart-by-userid',
                        'DELETE cart/{id}' => 'delete-cart-by-userid',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                    ],
                ],
                //LINHAS CARRINHO
                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'api/produtos-carrinho',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'PATCH update/{id}' => 'update-cart-line',
                        'POST create' => 'create-cart-lines',
                        'GET cartline/{id}' => 'get-cart-lines-by-cartid',
                        'DELETE cartline/{id}' => 'delete-cart-line',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
];
