<?php
use frontend\assets\AppAsset;
use frontend\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use frontend\models\Setting;
/* @var $this \yii\web\View */
/* @var $content string */

$visible = $this->context->showBookList ?  "visible" :  "none";
$lang = Yii::$app->language;

$settingInfo = Setting::findOne(1);

$isEnglish = false;
if ($lang == "en" || $lang == "") {
    $isEnglish = true;
}

$aboutUrl = $isEnglish ? "http://www.memoriesoffices.com/en/#our-team" : "http://www.memoriesoffices.com/#our-team";
$contactUrl = $isEnglish ? "http://www.memoriesoffices.com/en/suntickets/#contact-info" : "http://www.memoriesoffices.com/suntickets/#contact-info";

//AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?=$lang?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <?= $this->registerMetaTag([
            'name' => 'beachclub',
            'content' => 'Beachclubippocampo.rentals'
        ]);
    ?>
    <title>SunTickets - <?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

    <!-- Bootstrap Core CSS -->
    <link href="<?=Yii::getAlias('@web')?>/css/bootstrap.min.css" rel="stylesheet">

    <!--  display favicon -->
  <link rel="apple-touch-icon" sizes="180x180" href="<?=Yii::getAlias('@web')?>/img/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" href="<?=Yii::getAlias('@web')?>/img/favicon/favicon-32x32.png" sizes="32x32">
  <link rel="icon" type="image/png" href="<?=Yii::getAlias('@web')?>/img/favicon/favicon-16x16.png" sizes="16x16">
  <link rel="manifest" href="<?=Yii::getAlias('@web')?>/img/favicon/manifest.json">
  <link rel="mask-icon" href="<?=Yii::getAlias('@web')?>/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
  <meta name="theme-color" content="#ffffff">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/balloon-css/0.3.0/balloon.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-touchspin/3.1.2/jquery.bootstrap-touchspin.min.css">
    <link href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" rel="stylesheet">
    <link type="text/css" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/css/datepicker.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.2/moment.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-range/2.2.0/moment-range.min.js"></script> 
    <script src="<?=Yii::getAlias('@web')?>/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
      <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
      <script type="text/javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
      <script type="text/javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
      <script type="text/javascript" src="//cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
      <script type="text/javascript" src="//cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>

    <script async type="text/javascript" src="<?=Yii::getAlias('@web')?>/js/validator.js"></script>
    <script type="text/javascript" src="<?=Yii::getAlias('@web')?>/js/jquery.smoothZoom.min.js"></script>
    <script async  type="text/javascript" src="<?=Yii::getAlias('@web')?>/js/bootstrap-notify.min.js"></script> 
    <script async src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/js/bootstrap-select.min.js"></script>
    <script async src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-touchspin/3.1.2/jquery.bootstrap-touchspin.min.js"></script>
    <script src="<?=Yii::getAlias('@web')?>/js/js.cookie.js"> </script>
    
    <script src="<?=Yii::getAlias('@web')?>/js/cart.min.js"></script>
    <script src="<?=Yii::getAlias('@web')?>/js/app.js"></script>

    <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/css/style.css">
    <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/css/demostyle.css" media="screen">
    
    <script type="text/javascript">
    language = '<?=Yii::$app->language?>';
    if (language == "it") {
        var _iub = _iub || [];
        _iub.csConfiguration = {
            cookiePolicyId: 7924688,
            siteId: 621377,
            lang: "it"
        };
    }
    else {
        var _iub = _iub || [];
        _iub.csConfiguration = {
            cookiePolicyId: 7924689,
            siteId: 621377,
            lang: "en"
        };
    }
    </script>
    
    <script type="text/javascript" src="//cdn.iubenda.com/cookie_solution/safemode/iubenda_cs.js" charset="UTF-8" async></script>

    <!-- Google Analytics -->
    <script>
    window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
    ga('create', 'UA-86349913-1', {
      'cookieName': 'clientId',
      'cookieExpires': 60 * 60 * 24  // Time in seconds.
    });
    ga('send', 'pageview');
    </script>
    <script async src='https://www.google-analytics.com/analytics.js'></script>
    <!-- End Google Analytics -->
</head>
<body>
    <?php $this->beginBody() ?>
    <nav class="navbar navbar-orange navbar-static-top">
      <div class="container">
        <div class="">
           <ul class="nav navbar-nav lingua">
            <li><a href="https://www.facebook.com/MemoriesITCom/" class="fb"></a></li>
            <li class="<?php if(!$isEnglish) echo 'active'; ?>"><?php printFlags($lang, $this->context->paramKeys, $this->context->paramValues, array_values($this->context->languages)[1]); ?></li>
            <li class="<?php if($isEnglish) echo 'active'; ?>"><?php printFlags($lang, $this->context->paramKeys, $this->context->paramValues, array_values($this->context->languages)[0]); ?></li>
          </ul>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a class="reset-home" href="<?=Url::to(['site/index', 'lang' => $lang])?>">HOME</a></li>
            <li><a href="<?= Url::to(['site/price', 'lang' => $lang])?>"><?=Yii::t('messages', 'PRICE LIST')?></a></li>
            <li ><a class="booking-cart" href="<?=Url::to(['site/gotocart', 'lang' => $lang])?>"><?=Yii::t('messages', 'BOOKING CART')?></a></li>
            <li>
                <a href="<?= Yii::$app->urlManagerBackEnd->createUrl('site/login').'?lang='.Yii::$app->language?>" ><?=Yii::t('messages', 'LOG IN')?></a>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    <div class="container">
            <div class="row hidden-xs">
                <div class="col-sm-3">
                    <a class="reset-home" href="<?= Url::to(['site/index', 'lang' => $lang])?>">
                        <img alt="<?=Yii::$app->name?>" class="img-responsive" src="<?=Yii::getAlias('@web')?>/img/disponibile.png" />
                    </a>
                </div>
                <div class="col-sm-9">
                    <h1 class="slogan"><?=Yii::t('messages', 'ONLINE SUNSHADES BOOKING')?></h1>
                    <div class="row">
                    <div class="col-sm-8 ptsans"></div>
                    <div class="col-sm-4 ptsans align-right"><span class="orange phone">+<?=$settingInfo->phonenumber?></span><br><a class="orange mail" href="mailto:<?=$settingInfo->email?>"><?=$settingInfo->email?></a></div>
                    </div>
                </div>
            </div>
            <div class="row visible-xs">
                <div class="col-xs-6">
                    <h1 class="slogan_mobile"><?=Yii::t('messages', 'ONLINE SUNSHADES BOOKING')?></h1>
                </div>
                <div class="col-xs-6">
                    <a href="<?=Yii::$app->homeUrl?>">
                        <img alt="<?=Yii::$app->name?>" style="margin-left: 30px;" class="img-responsive" src="<?=Yii::getAlias('@web')?>/img/disponibile.png" />
                    </a>
                </div>
            </div>
        </div>
        <div class="container-fluid onda_azz">
            
        </div>

        <div class="container-fluid azz">
            <div class="container main-contaniner" style="width: 100%;">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </div>
        <div class="container visible-xs">
            <div class="col-xs-12 align-center">
                <img alt="payment" src="<?=Yii::getAlias('@web')?>/img/payment.jpg" class="payment"/>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 hidden-xs">
                    <h3 class="title_beige ptsans"><?=Yii::t('messages', 'Your commercial spot on web')?></h3>
                </div>
                
            </div>
        </div>
        <div class="container-fluid beige" style="padding-top: 15px; padding-bottom: 15px;">
            <div class="container min_height">
                <div class="embed-responsive embed-responsive-16by9 hidden-xs" >
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/AVIxVVSRMDo?rel=0" frameborder="0" allowfullscreen></iframe>
                </div>
                <div class="col-xs-12 brown visible-xs">
                    <p><?=Yii::t('messages', 'Your commercial spot on web')?><br><span class="">+<?=$settingInfo->phonenumber?></span><br/><a href="mailto: <?=$settingInfo->email?>"><?=$settingInfo->email?></a></p>
                </div>
                <div class="col-xs-12 visible-xs">
                    <div class="embed-responsive embed-responsive-16by9">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/AVIxVVSRMDo" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
                
            </div>
        </div>
        
        <footer class="container-fluid">
            <div class="container">
                <div class="row">
                    <div class="col-sm-4 hidden-xs">
                        <img alt="payment" src="<?=Yii::getAlias('@web')?>/img/payment.jpg" class="payment"/>
                    </div>
                    <div class="col-sm-6 align-right footer_middle">
                        <h3 class="footer raleway" style="margin-top: 0px;">&copy;SunTickets 2016</h3>
                        <div class="footer_menu">
                            <a href="<?= $aboutUrl?>"><?=
        Yii::t('messages', 'About Us')?></a> | <a href="<?=$contactUrl?>"><?=
        Yii::t('messages', 'Contact Us')?></a> 
                        </div>
                        <div class="footer_menu">
                             <a href="//www.iubenda.com/privacy-policy/<?php if(Yii::$app->language == 'en') echo '7924689';  else echo '7924688';?>" class="iubenda-nostyle no-brand iubenda-embed" title="Privacy Policy"><?=Yii::t('messages', 'Privacy Policy')?></a><script type="text/javascript">(function (w,d) {var loader = function () {var s = d.createElement("script"), tag = d.getElementsByTagName("script")[0]; s.src = "//cdn.iubenda.com/iubenda.js"; tag.parentNode.insertBefore(s,tag);}; if(w.addEventListener){w.addEventListener("load", loader, false);}else if(w.attachEvent){w.attachEvent("onload", loader);}else{w.onload = loader;}})(window, document);</script>
                             &nbsp;&nbsp;|&nbsp;&nbsp;
                             <a href="<?php if(Yii::$app->language == 'en') echo 'http://www.memoriesoffices.com/wp-content/uploads/2016/09/Regolamento-suntickets-eng.pdf'; else echo 'http://www.memoriesoffices.com/wp-content/uploads/2016/09/Regolamento-suntickets.pdf';  ?>" target="_blank"><?=Yii::t('messages', 'Terms & Conditions')?></a>

                        </div>
                    </div>
                    <div class="col-sm-2 hidden-xs">
                        <a href="http://memoriesoffices.com/" target="_blank"> <img alt="<?=Yii::$app->name?>" class="img-responsive logo_footer" src="<?=Yii::getAlias('@web')?>/img/disponibile.png" style="display: block" /></a>
                    </div>
                </div>
            </div>
        </footer>
        <div class="container-fluid footer_orange"></div>
        <div class="container-fluid footer_bottom" style="margin-top: -5px;">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 align-right">
                        <a href="http://www.suntickets.it" target="_blank"> 
                            <img alt="<?=Yii::$app->name?>" src="<?=Yii::getAlias('@web')?>/img/footer_logo.png" class="footer_sun "/>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    <?php $this->endBody() ?>
</body>
<div class="modal fade" id="myModal-warning" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content overlay" style=" overflow-x: hidden;">
        <div class="row modal-body">
            <div class="col-xs-2 col-sm-2 nopl left">
                <img alt="<?=Yii::$app->name?>" src="<?=Yii::getAlias('@web')?>/img/disponibile.png" />
                <div class="number p-l-8">C23</div>
            </div>
            <div class="col-xs-9 col-sm-9  left ">
                <div class="m-b-10">
                    <span class="sunshade-array"></span>
                    <div class="message-warning"><?=Yii::t('messages', 'There is no book list yet.')?></div>
                </div>
                
                <a class="warning-button add_sunshine modal-close cursor-pointer"><?=Yii::t('messages', 'OK')?>
                </a>
            </div>

            <div class="col-xs-1 col-sm-1 left nopl p-r-20 cursor-pointer">
                <i class="fa fa-close modal-close" aria-hidden="true"></i>
            </div>
        </div>
    </div>
  </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content overlay" style=" overflow-x: hidden;">
        <div class="row modal-body">
            <div class="col-xs-2 col-sm-2 nopl left">
                <img alt="<?=Yii::$app->name?>" src="<?=Yii::getAlias('@web')?>/img/disponibile.png" />
                <div class="number p-l-8">C23</div>
            </div>
            <div class="col-xs-9 col-sm-9 left ">
                <div class="message"></div>
                <form action="<?=Url::to(['site/gotocart'])?>">
                    <input type="hidden" name="lang" value="<?=Yii::$app->language?>">
                    <button type="submit" class="goto-cart"><i class="fa fa-shopping-cart" aria-hidden="true"></i> <?=Yii::t('messages', 'GO TO CART')?>
                    </button>
                </form>
            </div>

            <div class="col-xs-1 col-sm-1 left nopl p-r-20 cursor-pointer">
                <i class="fa fa-close modal-close" aria-hidden="true"></i>
            </div>
        </div>
    </div>
  </div>
</div>
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

    echo Html::a($language, $arrayUrl);
}
?>



