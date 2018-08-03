<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        // here you can set theme used for your frontend application 
        // - template comes with: 'default', 'slate', 'spacelab' and 'cerulean'
        'urlManagerBackEnd' => [
            'class' => 'yii\web\urlManager',
            'baseUrl' => '/sunticketsDemo/admin',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'view' => [
            'theme' => [
                'pathMap' => ['@app/views' => '@webroot/themes/cerulean/views'],
                'baseUrl' => '@web/themes/cerulean',
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\UserIdentity',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'i18n' => [
            'translations' => [
                'messages' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ]
            ],
        ],
        'paypal'=> [
            'class'        => 'common\component\Paypal',
            'clientId'     => 'AUtUnu_6egzotmx8u7m3mx8YPZUTdEWEZyiVc5cfvYHazCtiEaJabfzsLPIJgzPGFW5CIMIGf4KiLY_9',
            'clientSecret' => 'EMqjqp8w4Dbg4tX7GFcgnuEDruRSDMrlWc3Sto2NVAOrRIQykyPjnqHNqs5pd-NNHHAKGZxsBa9Hw10L',
            'isProduction' => false,
             // This is config file for the PayPal system
             'config'       => [
                 'http.ConnectionTimeOut' => 30,
                 'http.Retry'             => 1,
                 'mode'                   => \common\component\Paypal::MODE_SANDBOX,
                 'log.LogEnabled'         => YII_DEBUG ? 1 : 0,
                 'log.FileName'           => 'paypal.log',
                 'log.LogLevel'           => \common\component\Paypal::LOG_LEVEL_FINE,
            ]
        ],
    ],
    'params' => $params,
];
