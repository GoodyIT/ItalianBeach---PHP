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
            'clientSecret' => 'EPznnYQHmSbXB-5kt4exaGeYcQuHQ5hoNRtJpopgA_UyaUxyfh10xaVV_zqIDRANYGZe5YY8R8DsgtcV',
            'isProduction' => false,
             // This is config file for the PayPal system
             'config'       => [
                 'http.ConnectionTimeOut' => 30,
                 'http.Retry'             => 1,
                 'mode'                   => \common\component\Paypal::MODE_SANDBOX,
                 'log.LogEnabled'         => YII_DEBUG ? 0 : 0,
                 'log.FileName'           => '@runtime/logs/paypal.log',
                 'log.LogLevel'           => \common\component\Paypal::LOG_LEVEL_FINE,
            ]
        ],
    ],
    'params' => $params,
];
