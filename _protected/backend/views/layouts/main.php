<?php
$lang = Yii::$app->language;
$en = array_values($this->context->languages)[0];
$it = array_values($this->context->languages)[1];

$isEnglish = false;
if ($lang == "en" || $lang == "") {
    $isEnglish = true;
}


use backend\assets\AppAsset;
use frontend\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
//use yii\bootstrap\Nav;
//use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
//use machour\yii2\notifications\widgets\NotificationsWidget;


/* @var $this \yii\web\View */
/* @var $content string */

// AppAsset::register($this);

$baseurl = Yii::getAlias("@appRoot");//"http://www.beachclubippocampo.rentals";

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>

    <!--  display favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?=$baseurl?>/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="<?=$baseurl?>/images/favicon/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="<?=$baseurl?>/images/favicon/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="<?=$baseurl?>/images/favicon/manifest.json">
    <link rel="mask-icon" href="<?=$baseurl?>/images/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="theme-color" content="#ffffff">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <link href="<?=$baseurl?>/js/vendors/bootgrid/jquery.bootgrid.min.css" rel="stylesheet">

    <script src="<?=$baseurl?>/js/vendors/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="<?=$baseurl?>/js/yii.js"></script>
    <script src="<?=$baseurl?>/js/jquery-ui.js"></script>
    <script type="text/javascript" src="<?=$baseurl?>/js/bootstrap-notify.min.js"></script>
    <script src="<?=$baseurl?>/js/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?=$baseurl?>/js/moment.js"></script>

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
    <!-- Vendor CSS -->
    <link href="<?=$baseurl?>/js/vendors/bower_components/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet">
    <link href="<?=$baseurl?>/js/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
    <link href="<?=$baseurl?>/js/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
    <link href="<?=$baseurl?>/js/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
    <link href="<?=$baseurl?>/js/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">      

    <link href="<?=$baseurl?>/js/vendors/farbtastic/farbtastic.css" rel="stylesheet">
    <link href="<?=$baseurl?>/js/vendors/bower_components/chosen/chosen.min.css" rel="stylesheet">
    <link href="<?=$baseurl?>/js/vendors/summernote/dist/summernote.css" rel="stylesheet">  

    <!-- CSS -->
    <link href="<?=Yii::getAlias('@web')?>/css/app.min.1.css" rel="stylesheet">
    <link href="<?=Yii::getAlias('@web')?>/css/app.min.2.css" rel="stylesheet">
    <link href="<?=Yii::getAlias('@web')?>/css/app.min.2.css" rel="stylesheet">

    <script src="<?=$baseurl?>/js/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="<?=$baseurl?>/js/vendors/bower_components/Waves/dist/waves.min.js"></script>
    <script src="<?=$baseurl?>/js/vendors/bootstrap-growl/bootstrap-growl.min.js"></script>
    <script src="<?=$baseurl?>/js/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.min.js"></script>
    <script src="<?=$baseurl?>/js/vendors/bower_components/moment/min/moment.min.js"></script>
    <script src="<?=$baseurl?>/js/vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.js"></script>
    <script src="<?=$baseurl?>/js/vendors/bower_components/nouislider/distribute/jquery.nouislider.all.min.js"></script>
    <script src="<?=$baseurl?>/js/vendors/bower_components/typeahead.js/dist/typeahead.bundle.min.js"></script>
    <script src="<?=$baseurl?>/js/vendors/bootgrid/jquery.bootgrid.updated.min.js"></script>
    <!-- Placeholder for IE9 -->
    <!--[if IE 9 ]>
        <script src="vendors/bower_components/jquery-placeholder/jquery.placeholder.min.js"></script>
    <![endif]-->

    <script type="text/javascript" src="<?=$baseurl?>/js/jquery.mousewheel.min.js"></script>
    <script type="text/javascript" src="<?=$baseurl?>/js/jquery.jscrollpane.min.js"></script>

    <script src="<?=$baseurl?>/js/vendors/bower_components/chosen/chosen.jquery.min.js"></script>
    <script src="<?=$baseurl?>/js/vendors/fileinput/fileinput.min.js"></script>
    <script src="<?=$baseurl?>/js/vendors/input-mask/input-mask.min.js"></script>
    <script src="<?=$baseurl?>/js/vendors/farbtastic/farbtastic.min.js"></script>
    
    <script src="<?=$baseurl?>/js/functions.js"></script>
    <script src="<?=$baseurl?>/js/charts.js"></script>

    <style type="text/css">
        .divider{
                height: 1px;
                margin: 8px 0;
                overflow: hidden;
                background-color: rgba(0, 0, 0, 0.08);
        }

        .outerContainer {
            min-height: 0px;
            height: auto;
            margin: 0 auto -60px;
            padding: 0 0 60px;
            background-color: #0783C3;
        }
        .lang-active {
            background-color: #f6675d 
        }
    </style>

    <script type="text/javascript">

    ;(function($) {
    $(document).ready(function() {


        
            var delayTime = 0;
            function notify(title, message){
               // $.notifyClose('all');
                $.notifyDefaults({
                    placement: {
                        from:'top',
                        align:'center'
                    },
                    animate: {
                        enter: 'animated bounceInDown',
                        exit: 'animated bounceOutUp'
                    },
                    offset: {
                        x: 20,
                        y: 50
                    }
                });
                $.notify({
                    message: "<strong>" + title + "</strong> <br/>" + message
                },{
                    type: 'danger',
                    allow_dismiss: true,    
                    delay: 10000,
                });
            };
            

             var refreshId = setInterval(function(){ refreshNotification(); console.log('refresh notification 1'); }, 10000);

            /*
             * Clear Notification
             */
            $('body').on('click', '[data-clear="notification"]', function(e){
              e.preventDefault();

              clearInterval(refreshId);

              var x = $(this).closest('.listview');
              var y = x.find('.lv-item');
              var z = y.size();

              $(this).parent().fadeOut();

              x.find('.list-group').prepend('<i class="grid-loading hide-it"></i>');
              x.find('.grid-loading').fadeIn(1500);


              var w = 0;
              var listOfIds = [];
              y.each(function(){
                  var z = $(this);
                  listOfIds.push(z.attr('data-notification-index'));
                  setTimeout(function(){
                  z.addClass('animated fadeOutRightBig').delay(1000).queue(function(){
                      z.remove();
                  });
                  }, w+=150);
              })

              $('.notification-count').addClass('hide').html('');

              $.ajax({
                   url: '<?= Yii::$app->request->baseUrl. '/notification/updatenotification' ?>',
                   type: 'post',
                   data: {
                        listOfIds: listOfIds,
                        _csrf: '<?=Yii::$app->request->getCsrfToken()?>'
                   },
                   success: function (data) {
                        //Popup empty message
                        setTimeout(function(){
                          //  $('#notifications').addClass('empty');
                            setInterval(function(){ refreshNotification(); console.log('refresh notification 2');  }, 10000);
                        }, (z*150)+200);
                   }
               });

          });
           /* function changeFavicon() {
                document.getElementById('favicon').href = iconNew;
            }*/

            var isShownObj1 = false, isShownObj2 = false, isShownObj3 = false, isShownObj4 = false; 
            function refreshNotification() {
                $.ajax({
                   url: '<?= Yii::$app->request->baseUrl. '/notification/' ?>',
                   data: {lang: '<?=Yii::$app->language?>'},
                   type: 'get',
                   success: function (data) {
                      var jsonObject = JSON.parse(data); 
                      var length = 0;
                       if (jsonObject.notification.length !== 0 && !isShownObj1) {
                            obj1 = jsonObject.notification;
                            $('.notification-body').html('');
                            $('#notifications').removeClass('empty'); 
                            $('#notifications .lv-header ul li').show();
                            length += obj1.length;
                            var idx1 = 0;
                            for (var i = obj1.length - 1; i >= 0; i--) {
                                $('.notification-item-template.hide .notification-username').html(obj1[i].username);
                                $('.notification-item-template.hide .notification-content').html(obj1[i].title);
                                $('.notification-item-template.hide a.lv-item').attr('data-notification-index', obj1[i].notiId);
                                $clone = $('.notification-item-template a').clone().removeClass('hide').attr('href', '<?=Url::to(["bookinfo/view"])?>' + '?id=' + obj1[i].Id + '&lang=' + '<?=Yii::$app->language?>');
                                $('.notification-body').append($clone);
                                
                                delayTime += 3000;
                                setTimeout(function(){
                                    notify(obj1[idx1].username, obj1[idx1].title);
                                    idx1++
                                }, delayTime);
                                isShownObj1 = true;
                            }

                            obj2 = jsonObject.onedayAvailable;
                            var idx2 = 0;
                            if (obj2 != undefined && obj2.length !== 0 && !isShownObj2) {
                                <?php
                                    $message = Yii::t('messages', ' will be available 1 day later');
                                ?>
                                length += obj2.length;
                                for (var i = obj2.length - 1; i >= 0; i--) {
                                    $('.notification-item-template.hide .notification-username').html(obj2[i].seat);
                                    $('.notification-item-template.hide .notification-content').html('<?=Yii::t("messages", "Will be available again 1 day later!")?>');
                                    $('.notification-item-template.hide .notification-item-template a.lv-item').attr('data-notification-index', obj2[i].Id);
                                    $clone = $('.notification-item-template a').clone().removeClass('hide').attr('href', '<?=Url::to(["bookinfo/view"])?>' + '?id=' + obj2[i].Id + '&lang=' + '<?=Yii::$app->language?>');
                                    $('.notification-body').append($clone);

                                    delayTime += 3000;
                                    setTimeout(function(){
                                        notify(obj2[idx2].seat, '<?=$message?>');
                                        idx2++;
                                    }, delayTime);
                                    isShownObj2 = true;
                                }
                            }

                            obj3 = jsonObject.twodayAvailable;
                            var idx3 = 0;
                            if (obj3 != undefined && obj3.length !== 0 && !isShownObj3) {
                                <?php
                                    $message = Yii::t('messages', ' will be available 2 day later');
                                ?>
                                length += obj3.length;
                                for (var i = obj3.length - 1; i >= 0; i--) {
                                    $('.notification-item-template.hide .notification-username').html(obj3[i].seat);
                                    $('.notification-item-template.hide .notification-content').html('<?=Yii::t("messages", "Will be available again 1 day later!")?>');
                                    $('.notification-item-template.hide .notification-item-template a.lv-item').attr('data-notification-index', obj3[i].Id);
                                     $clone = $('.notification-item-template a').clone().removeClass('hide').attr('href', '<?=Url::to(["bookinfo/view"])?>' + '?id=' + obj3[i].Id + '&lang=' + '<?=Yii::$app->language?>');
                                    $('.notification-body').append($clone);

                                    delayTime += 3000;

                                    setTimeout(function(){
                                        notify(obj3[idx3].seat, '<?=$message?>');
                                        idx3++;
                                    }, delayTime);

                                    isShownObj3 = true;
                                }
                            }

                            obj4 = jsonObject.todayAvailable;
                            var idx4 = 0;
                            if (obj4 != undefined && obj4.length !== 0 && !isShownObj4) {
                                <?php
                                    $message = Yii::t('messages', ' will be available today');
                                ?>
                                length += obj4.length;
                                for (var i = obj4.length - 1; i >= 0; i--) {
                                    $('.notification-item-template.hide .notification-username').html(obj4[i].seat);
                                    $('.notification-item-template.hide .notification-content').html('<?=Yii::t("messages", "Will be available again 1 day later!")?>');
                                    $('.notification-item-template.hide .notification-item-template a.lv-item').attr('data-notification-index', obj4[i].Id);
                                     $clone = $('.notification-item-template a').clone().removeClass('hide').attr('href', '<?=Url::to(["bookinfo/view"])?>' + '?id=' + obj4[i].Id + '&lang=' + '<?=Yii::$app->language?>');
                                    $('.notification-body').append($clone);

                                    delayTime += 3000;

                                    setTimeout(function(){
                                        notify(obj4[idx4].name, '<?=$message?>');
                                        idx4++;
                                    }, delayTime);

                                    isShownObj4 = true;
                                }
                            }

                            $('.notification-count').removeClass('hide').html(length);

                            // $(".notification-body").jScrollPane();
                        } 
                        
                        else {
                         //  $('#notifications').addClass('empty'); 
                        }
                     }
              });
            }
        });
    })(jQuery);
    </script>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    
        <header id="header" class="clearfix" data-current-skin="blue">
            <ul class="header-inner">
                <li id="menu-trigger" data-trigger="#sidebar">
                    <div class="line-wrap">
                        <div class="line top"></div>
                        <div class="line center"></div>
                        <div class="line bottom"></div>
                    </div>
                </li>
            
                <li class="hidden-xs">
                    <a href="<?= Url::to(['dashboard/index', 'lang' => $lang])?>" ><img class="p-b-10" src="<?=$baseurl?>/images/logo-mobile.png" alt="<?=Yii::t('messages', Yii::$app->name)?>" style="border-radius: 50%;"></a>
                </li>
                
                <li class="pull-right">
                    <ul class="top-menu">
                        <li id="toggle-width">
                            <div class="toggle-switch">
                                <input id="tw-switch" type="checkbox" hidden="hidden">
                                <label for="tw-switch" class="ts-helper"></label>
                            </div>
                        </li>

                        <li class="dropdown">
                            <a data-toggle="dropdown" href="">
                                <i class="tm-icon zmdi zmdi-notifications"></i>
                                <i class="tmn-counts notification-count hide"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-lg pull-right">
                                <div class="listview" id="notifications">
                                    <div class="lv-header">
                                        <?=Yii::t('messages', 'Notification')?>
                        
                                        <ul class="actions">
                                            <li class="dropdown">
                                                <a href="" data-clear="notification">
                                                    <i class="zmdi zmdi-check-all"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="notification-body scroll-pane" style="overflow-y: auto; height: 300px;">
                                    </div>
                                </div>
                            </div>
                                <div class="notification-item-template hide">
                                    <a class="lv-item" href="">
                                        <div class="media">
                                            <div class="pull-left">
                                                <img class="lv-img-sm" src="<?=$baseurl?>/images/logo.png" alt="">
                                            </div>
                                            <div class="media-body">
                                                <div class="lv-title notification-username">David Belle</div>
                                                <small class="lv-small notification-content">Booked sunshade A1</small>
                                            </div>
                                        </div>
                                    </a>   
                                </div>
                        </li>

                    </ul>
                </li>
            </ul>
        </header>
        
    <section id="main" data-layout="layout-1">
         <aside id="sidebar" class="sidebar c-overflow">
            <div class="profile-menu" style="padding-top: 8px;">
                <a href="#">
                     <div class="profile-pic" style="height: 71px;">
                    </div>
                    <div class="profile-info">
                         <?php if (!Yii::$app->user->isGuest) echo Yii::$app->user->identity->username;  ?>
                    </div>
                </a>
            </div>

            <ul class="main-menu">
                <li><a href="<?= Url::to(['dashboard/index', 'lang' => $lang])?>"><i class="zmdi zmdi-home"></i> <?=Yii::t('messages', 'Dashboard')?></a></li>
                <li class="divider"></li>

                <?php $this->beginBlock('adminBlock') ?>
                    <li><a href="<?=Url::to(['guest/sendbookinfo', 'lang' => $lang])?>"><i class="fa fa-print fa-2"></i> <?=Yii::t('messages', 'Book Info (send / print)')?></a></li>
                    <li><a href="<?=Url::to(['guest/guestinfo', 'lang' => $lang])?>"><i class="zmdi zmdi-accounts-list zmdi-hc-fw"></i> <?=Yii::t('messages', 'Customers Info')?></a></li>
                    <li><a href="<?=Url::to(['bookinfo/index', 'lang' => $lang])?>"><i class="zmdi zmdi-book-image zmdi-hc-fw"></i><?=Yii::t('messages', 'Sunshade Book Management')?></a></li>
                    <li><a href="<?=Url::to(['bookinfo/roombook', 'lang' => $lang])?>"><i class="fa fa-bed c-lightblack"></i><?=Yii::t('messages', 'Room Book Management')?></a></li>
                    <li><a href="<?=Url::to(['price/index', 'lang' => $lang])?>"><i class="fa fa-eur fa-2"></i> <?=Yii::t('messages', 'Price Management')?></a></li>
                    <li><a href="<?=Url::to(['setting/update', 'lang' => $lang])?>"><i class="zmdi zmdi-settings zmdi-hc-fw"></i> <?= Yii::t('messages', 'General Settings')?></a></li>
                    <li><a href="<?=Url::to(['user/index', 'lang' => $lang])?>"><i class="fa fa-user fa-2"></i> <?= Yii::t('messages', 'User Management')?></a></li>
                <?php $this->endBlock() ?>
                <?php if (Yii::$app->user->can('admin')) echo $this->blocks['adminBlock']  ?>

                <?php $this->beginBlock('guestBlock') ?>
                    <li><a href="<?=Url::to(['site/login', 'lang' => $lang])?>"><i class="fa fa-sign-in fa-2"></i> <?=Yii::t('messages', 'Login')?></a></li>
                <?php $this->endBlock() ?>
                <?php if (Yii::$app->user->isGuest) echo $this->blocks['guestBlock']  ?>
                    
                <?php $this->beginBlock('nonGuestBlock') ?>
                    <li class="divider"></li>
                    <li><a href="<?=Url::to(['site/logout'])?>" data-method="post"><i class="fa fa-sign-out fa-2"></i> <?=Yii::t('messages', 'Logout')?></a></li>
                <?php $this->endBlock() ?>
                <?php if (!Yii::$app->user->isGuest) echo $this->blocks['nonGuestBlock']  ?>
                <li class="divider"></li>
                     
                <li><a href="<?php printFlags($this->context->paramKeys, $this->context->paramValues, $en)?>" class="<?php if($isEnglish) echo 'lang-active';?>"><img class="selectedflag" src="<?=$baseurl?>/images/flags/en.gif" alt="en"> <?=Yii::t('messages', 'English')?></a></li>
                <li><a href="<?php printFlags($this->context->paramKeys, $this->context->paramValues, $it)?>" class="<?php if(!$isEnglish) echo 'lang-active';?>""><img class="selectedflag" src="<?=$baseurl?>/images/flags/it.gif" alt="it"> <?=Yii::t('messages', 'Italian')?></a></li>
            </ul>
        </aside>

            <section id="content">
                <div class="container">
                    <?= Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        'homeLink' =>  ['label' => Yii::t("messages", "Home"), 'url' => ['dashboard/index', 'lang'=>$lang]],
                    ]) ?>
                    <?= Alert::widget() ?>
                    <?= $content ?>
                </div>
            </section>
    </section>
    <footer id="footer">
            Copyright &copy; <?= Yii::t('messages', Yii::$app->name) ?> <?= date('Y')?>
            <ul class="f-menu">
                <li><a href="<?= Url::to(['dashboard/index', 'lang' => $lang])?>"><i class="zmdi zmdi-home"></i> <?=Yii::t('messages', 'Dashboard')?></a></li>
                    <li class="divider"></li>
                <?php $this->beginBlock('adminBlock') ?>
                    <li><a href="<?=Url::to(['guest/sendbookinfo', 'lang' => $lang])?>"><i class="fa fa-print fa-2"></i> <?=Yii::t('messages', 'Send / Print Book Info')?></a></li>
                    <li><a href="<?=Url::to(['bookinfo/index', 'lang' => $lang])?>"><i class="zmdi zmdi-book-image zmdi-hc-fw"></i><?=Yii::t('messages', 'Book Management')?></a></li>
                    <li><a href="<?=Url::to(['price/index', 'lang' => $lang])?>"><i class="fa fa-eur fa-2"></i> <?=Yii::t('messages', 'Price Management')?></a></li>
                    <li><a href="<?=Url::to(['setting/update', 'lang' => $lang])?>"><i class="zmdi zmdi-settings zmdi-hc-fw"></i> <?= Yii::t('messages', 'General Settings')?></a></li>
                    <li><a href="<?=Url::to(['user/index', 'lang' => $lang])?>"><i class="fa fa-user fa-2"></i> <?= Yii::t('messages', 'User Management')?></a></li>
                <?php $this->endBlock() ?>
                <?php if (Yii::$app->user->can('admin')) echo $this->blocks['adminBlock']  ?>
            </ul>
    </footer>

        <!-- Page Loader -->
        <div class="page-loader">
            <div class="preloader pls-blue">
                <svg class="pl-circular" viewBox="25 25 50 50">
                    <circle class="plc-path" cx="50" cy="50" r="20" />
                </svg>

                <p>Please wait...</p>
            </div>
        </div>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
<?php
function printFlags($paramKeys, $paramValues, $language)
{
    $arrayUrl = array();

    $arrayUrl[0] = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;

    $idExist = false;
    foreach (Yii::$app->controller->actionParams  as $key => $value) {
        if ($key == "id") {
            $idExist = true;
            $arrayUrl[0] .= "?" . $key . "=" . $value . "&lang=" . $language;
        }
    }

    if (!$idExist) {
         $arrayUrl[0] .= "?lang=" . $language;
    }
    for($i = 0; $i < count($paramKeys); $i++)
        if($paramKeys[$i] !== 'lang')
            $arrayUrl[$paramKeys[$i]] = $paramValues[$i];

    return print_r(Url::to([$arrayUrl[0]]));
}
?>

<!-- Javascript Libraries -->
       