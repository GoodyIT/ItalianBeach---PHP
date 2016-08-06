<?php
use frontend\assets\AppAsset;
use frontend\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

$visible = $this->context->showBookList ?  "visible" :  "none";
$lang = Yii::$app->language;

use frontend\models\Setting;

$settingInfo = Setting::findOne(1);

$isEnglish = false;
if ($lang == "en" || $lang == "") {
    $isEnglish = true;
}

//AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

     <!-- display favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?=Yii::getAlias('@appRoot')?>/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="<?=Yii::getAlias('@appRoot')?>/images/favicon/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="<?=Yii::getAlias('@appRoot')?>/images/favicon/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="<?=Yii::getAlias('@appRoot')?>/images/favicon/manifest.json">
    <link rel="mask-icon" href="<?=Yii::getAlias('@appRoot')?>/images/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="theme-color" content="#ffffff">

    
    <link href="<?=Yii::getAlias('@web')?>/css/style.min.css" rel="stylesheet" />

    <link href='https://fonts.googleapis.com/css?family=Knewave' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <link href="<?=Yii::getAlias('@web')?>/js/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">

    
     <link href="<?=Yii::getAlias('@web')?>/css/app.min.1.css" rel="stylesheet">
    <link href="<?=Yii::getAlias('@web')?>/css/app.min.2.css" rel="stylesheet">

    
    <script src="<?=Yii::getAlias('@web')?>/js/jquery.min.js"></script>
     <script src="<?=Yii::getAlias('@web')?>/js/jquery-ui.js"></script>
    <script src="<?=Yii::getAlias('@web')?>/js/yii.js"></script> 
    <script src="<?=Yii::getAlias('@web')?>/js/moment.js"></script> 
    

     <script type="text/javascript" src="<?=Yii::getAlias('@web')?>/js/bootstrap-notify.min.js"></script>  
     
    <script type="text/javascript" src="<?=Yii::getAlias('@web')?>/libs/jquery.fs.zoetrope.min.js"></script>
    <script type="text/javascript" src="<?=Yii::getAlias('@web')?>/js/toe.min.js"></script>
    <script type="text/javascript" src="<?=Yii::getAlias('@web')?>/js/jquery.mousewheel.min.js"></script>
    <script type="text/javascript" src="<?=Yii::getAlias('@web')?>/js/imgViewer.min.js"></script>
    <script type="text/javascript" src="<?=Yii::getAlias('@web')?>/src/imgNotes.js"></script>  

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
    <script src="<?=Yii::getAlias('@web')?>/js/vendors/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?=Yii::getAlias('@web')?>/js/vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="<?=Yii::getAlias('@web')?>/js/vendors/Waves/waves.min.js"></script>
    <script src="<?=Yii::getAlias('@web')?>/js/functions.js"></script>

    <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/css/demostyles.css" media="screen">
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="outerContainer">
    <header id="header-2" class="clearfix" data-current-skin="lightblue"> <!-- Make sure to change both class and data-current-skin when switching sking manually -->
            <ul class="header-inner clearfix">
                <li id="menu-trigger" data-trigger=".ha-menu" class="visible-xs">
                    <div class="line-wrap">
                        <div class="line top"></div>
                        <div class="line center"></div>
                        <div class="line bottom"></div>
                    </div>
                </li>
            
                <li class="pull-left">
                    <ul class="top-menu">
                        <li class="waves-effect"> 
                            <a class="web-logo" href="<?= Yii::$app->urlManager->createUrl(["/"]) ?>" title="<?=$settingInfo->propertytitle?>"><img class="selectedflag" src="<?=Yii::getAlias('@web')?>/images/logo.png" alt="beachclubippocampo.it" style="border-radius: 50%;">
                            <span class="hidden-xs" style="font-size: 20px; font-family: helvitica;"> <?= Yii::t('messages', 'SUNSHADE BOOKING')?> </span>
                            </a>
                        </li>
                    </ul>
                </li>     

                <li class="pull-right">
                    <ul class="top-menu">
                        <li class="waves-effect"><i class="fa fa-bed c-black" style="font-size: 20px;  text-shadow: 1px 1px 1px #ccc;"></i><span class="hidden-xs" style="color:white"> via del Lido sn loc. Ippocampo 71043 Manfredonia</span></li>
                       <li class="waves-effect"><a href="tel:<?=$settingInfo->phonenumber?>"> <i class="fa fa-phone c-black" style="font-size: 20px; text-shadow: 1px 1px 1px #ccc;"></i><span class="hidden-xs" style="color:white"> +<?=$settingInfo->phonenumber?></span></a></li>
                       <li class="waves-effect"><a href="mailto:<?=$settingInfo->email?>"><i class="fa fa-envelope c-black" style="font-size: 20px; text-shadow: 1px 1px 1px #ccc;"></i> <span class="hidden-xs" style="color:white"><?=$settingInfo->email?></span></a></li>
                    </ul>
                </li> 
            </ul>

            <nav class="ha-menu">
                <ul>
                    <li class="waves-effect active"><a href="<?=Yii::$app->homeUrl?>" ><i class="fa fa-home" style="font-size:20px"></i>  <?=Yii::t('messages', 'Home')?></a></li>
                    <li class="waves-effect"><a href="http://www.beachclubippocampo.it" target="_blank"><i class="fa fa-reply" style="font-size:20px"></i>   Beach Club Ippocampo.it</a></li>
                    <li class="waves-effect"><?= Html::a('<i class="fa fa-shopping-cart" aria-hidden="true" style="font-size:20px"></i> '. Yii::t('messages', 'layout.mybooklist'), 'javascript:void(0)', [ 'class' => 'booklist', 'style' =>['display'=>$visible]]); ?></li>
                    <li class="waves-effect"><?= Html::a('<i class="fa fa-info-circle" style="font-size:20px"></i> '. Yii::t('messages', 'PRICE LIST'), Yii::$app->urlManager->createUrl(['site/info'])); ?></li>
                    <li class="waves-effect <?php if($isEnglish) echo 'active';?>" ><?php printFlags($lang, $this->context->paramKeys, $this->context->paramValues, array_values($this->context->languages)[0]); ?></a></li>
                    <li class="waves-effect <?php if(!$isEnglish) echo 'active';?>"><?php printFlags($lang, $this->context->paramKeys, $this->context->paramValues, array_values($this->context->languages)[1]); ?></li>
                </ul>
            </nav>
    </header>
    
    <section id="main">
        <section id="content">
            <div class="container">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </section>
    </section>
    
    <footer id="footer">
        <div class="row">
            <div class="col-sm-5" style="margin-left: 40px">
                <div class="text-uppercase footer-right-title m-b-10 p-b-10 f-18" style="box-shadow: inset 0px -3px 0 0px rgba(0, 0, 0, 0.2); text-transform: uppercase;"> Develop And Design By MEMORIES SRLS IT07768220720 </div>
                <div class="text-uppercase m-b-10 f-18">&copy;<a style="text-decoration: none !important;" href="<?= Yii::$app->urlManager->createUrl(["/"]) ?>" title="<?= Yii::t('messages', Yii::$app->name) ?> "><?= Yii::t('messages', Yii::$app->name) ?> <?= date('Y') ?></a></div>
                <div class="text-uppercase m-b-10 f-18">
                    <a href="<?= Url::to(['site/about', 'lang' => $lang])?>"><i class="zmdi zmdi-home"></i> <?=Yii::t('messages', 'About us')?></a>
                </div>
                <div class="text-uppercase m-b-10 f-18">
                    <a href="<?=Url::to(['site/contact', 'lang' => $lang])?>"><i class="zmdi zmdi-email"></i> <?=Yii::t('messages', 'Contact us')?></a>
                </div>
                <div class="text-uppercase m-b-10 f-18">
                    <a href="http://www.beachclubippocampo.it" target="_blank"><i class="fa fa-reply" style="font-size:20px"></i>   Beach Club Ippocampo.it</a>
                </div>
            </div>
            <div class="col-sm-3">
                 <img src="https://www.paypalobjects.com/webstatic/mktg/logo-center/logo_paypal_pagamento.jpg" border="0" alt="" class="panel-img" style="width: 110%">
            </div>
            <div class="col-sm-3">
                <a href="http://memoriesoffices.com/" target="_blank"> <img src="http://memoriesoffices.com/wp-content/uploads/2016/06/logo.jpg" alt="Memories SRLS" style="border-radius: 5px; width: 75%"></a>
            </div>
        </div>       
    </footer>
    </div>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
<?php
function printFlags($lang, $paramKeys, $paramValues, $language)
{
    $arrayUrl = array();

    $arrayUrl[0] = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
    for($i = 0; $i < count($paramKeys); $i++)
        if($paramKeys[$i] !== 'lang')
            $arrayUrl[$paramKeys[$i]] = $paramValues[$i];
    $arrayUrl['lang'] = $language;

    echo Html::a(Html::img('@web/images/flags/' . $language . '.gif', array('alt' => $language, 'class' => ($lang === $language ? 'selectedflag' : '')))
        ,$arrayUrl);

}
?>
