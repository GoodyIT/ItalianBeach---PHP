<?php

$lang = Yii::$app->language;
$en = array_values($this->context->languages)[0];
$it = array_values($this->context->languages)[1];

$isEnglish = false;
if ($lang == "en" || $lang == "") {
    $isEnglish = true;
}

use frontend\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

// AppAsset::register($this);

$baseurl = Yii::getAlias("@web")."/..";
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
  <link rel="apple-touch-icon" sizes="180x180" href="<?=$baseurl?>/img/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" href="<?=$baseurl?>/img/favicon/favicon-32x32.png" sizes="32x32">
  <link rel="icon" type="image/png" href="<?=$baseurl?>/img/favicon/favicon-16x16.png" sizes="16x16">
  <link rel="manifest" href="<?=$baseurl?>/img/favicon/manifest.json">
  <link rel="mask-icon" href="<?=$baseurl?>/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
  <meta name="theme-color" content="#ffffff">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?=$baseurl?>/css/bootstrap.min.css">
  <link href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" rel="stylesheet">
  <link type="text/css" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css" rel="stylesheet" />

  <!-- Vendor CSS -->
  <link href="<?=$baseurl?>/js/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
  <link href="<?=$baseurl?>/js/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
  <link href="<?=$baseurl?>/js/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
  <link href="<?=$baseurl?>/js/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">

  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-touchspin/3.1.2/jquery.bootstrap-touchspin.min.css">
  <link rel="stylesheet" href="<?=$baseurl?>/css/datepicker.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/balloon-css/0.3.0/balloon.min.css">
  <link href="<?=$baseurl?>/js/vendors/farbtastic/farbtastic.css" rel="stylesheet">
  <link href="<?=$baseurl?>/js/vendors/bower_components/chosen/chosen.min.css" rel="stylesheet">
  <link href="<?=$baseurl?>/js/vendors/summernote/dist/summernote.css" rel="stylesheet">  
   
  <!-- CSS -->
  <link href="<?=Yii::getAlias('@web')?>/css/app.1.css" rel="stylesheet">
  <link href="<?=Yii::getAlias('@web')?>/css/app.2.css" rel="stylesheet">  
  <link rel="stylesheet" href="<?=$baseurl?>/css/demostyle.css" media="screen">

  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="<?=$baseurl?>/js/jquery-ui.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.2/moment.min.js"></script> 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-range/2.2.0/moment-range.min.js"></script>
  <script src="<?=$baseurl?>/js/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
  <script type="text/javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
  <script type="text/javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
  <script type="text/javascript" src="//cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
  <script type="text/javascript" src="//cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>

  <script src="<?=$baseurl?>/js/yii.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.js"></script>
  <script src="<?=$baseurl?>/js/vendors/bower_components/Waves/dist/waves.min.js"></script>
  <script src="<?=$baseurl?>/js/vendors/bower_components/autosize/dist/autosize.min.js"></script>
  <script type="text/javascript" src="<?=$baseurl?>/js/bootstrap-notify.min.js"></script>  
  <script type="text/javascript" src="<?=$baseurl?>/js/jQuery.print.js"></script>

  <script src="<?=$baseurl?>/js/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
  <script src="<?=$baseurl?>/js/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.min.js"></script>
  <script src="<?=$baseurl?>/js/vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
  <script type="text/javascript" src="<?=$baseurl?>/js/jquery.smoothZoom.min.js"></script>
  <script src="<?=$baseurl?>/js/vendors/bower_components/typeahead.js/dist/typeahead.bundle.min.js"></script>
  <script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.0.3/js/dataTables.checkboxes.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-touchspin/3.1.2/jquery.bootstrap-touchspin.min.js"></script>

 <script src="<?=$baseurl?>/js/vendors/sparklines/jquery.sparkline.min.js"></script>
 <script src="<?=$baseurl?>/js/vendors/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
 <script src="<?=$baseurl?>/js/vendors/bower_components/flot/jquery.flot.js"></script>
<script src="<?=$baseurl?>/js/vendors/bower_components/flot/jquery.flot.time.js"></script>
<script src="<?=$baseurl?>/js/vendors/bower_components/flot/jquery.flot.resize.js"></script>
<script src="<?=$baseurl?>/js/vendors/bower_components/flot/jquery.flot.pie.js"></script>
<script src="<?=$baseurl?>/js/vendors/bower_components/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

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
  <script src="<?=$baseurl?>/js/js.cookie.js"> </script>

  <script src="<?=$baseurl?>/js/charts.js"></script>
  <script src="<?=Yii::getAlias('@web')?>/js/functions.min.js"></script>
  <script src="<?=$baseurl?>/js/cart.min.js"></script>
  <script src="<?=$baseurl?>/js/app.js"></script>

  <script type="text/javascript">
    ;(function($) {
    $(document).ready(function() {
      var delayTime = 0;

    <?php if (!Yii::$app->user->isGuest) : ?>
      var refreshId = setInterval(function(){ refreshNotification(); }, 10000);

      function notify(username, title){
        $.notify(
          { message: '<strong>' + username + '</strong><br>' + title },
          {
            offset: {
              x: 10,
              y: 50
            },
            animate: {
              enter: 'animated bounceIn',
              exit: 'animated bounceOut'
            },
            type: 'danger'
          });
      };

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
                  console.log(data);
                  //Popup empty message
                  setTimeout(function(){
                    //  $('#notifications').addClass('empty');
                      setInterval(function(){ refreshNotification(); console.log('refresh notification 2');  }, 10000);
                  }, (z*150)+200);
             }
         });
      });

      <?php endif ?>
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
                          isShownObj1 = true;
                          $('.notification-body').html('');
                          $('#notifications').removeClass('empty'); 
                          $('#notifications .lv-header ul li').show();
                          length += obj1.length;
                          var idx1 = 0;
                          for (var i = obj1.length - 1; i >= 0; i--) {
                              $('.notification-item-template.hide .notification-username').html(obj1[i].username);
                              $('.notification-item-template.hide .notification-content').html(obj1[i].title);
                              $('.notification-item-template.hide a.lv-item').attr('data-notification-index', obj1[i].notiId);
                              $clone = $('.notification-item-template a').clone().removeClass('hide').attr('href', '<?=Url::to(["book-lookup/view"])?>' + '?sunshadeId=' + obj1[i].sunshadeId + '&bookId=' + obj1[i].bookId + '&guestId=' + obj1[i].guestId + '&lang=' + '<?=Yii::$app->language?>');
                              $('.notification-body').append($clone);
                              
                              delayTime += 3000;
                              setTimeout(function(){
                                  notify(obj1[idx1].username, obj1[idx1].title);
                                  idx1++
                              }, delayTime);
                              // isShownObj1 = true;
                          }

                          obj2 = jsonObject.onedayAvailable;
                          var idx2 = 0;
                          if (obj2 != undefined && obj2.length !== 0 && !isShownObj2) {
                            isShownObj2 = true;
                              <?php
                                  $message = Yii::t('messages', ' will be available 1 day later');
                              ?>
                              length += obj2.length;
                              for (var i = obj2.length - 1; i >= 0; i--) {
                                  $('.notification-item-template.hide .notification-username').html(obj2[i].sunshade);
                                  $('.notification-item-template.hide .notification-content').html('<?=$message?>');
                                  $('.notification-item-template.hide .notification-item-template a.lv-item').attr('data-notification-index', obj2[i].lookupId);
                                  $clone = $('.notification-item-template a').clone().removeClass('hide').attr('href', '<?=Url::to(["book-lookup/view"])?>' + '?sunshadeId=' + obj2[i].sunshadeId + '&bookId=' + obj2[i].bookId + '&guestId=' + obj2[i].guestId + '&lang=' + '<?=Yii::$app->language?>');
                                  $('.notification-body').append($clone);

                                  delayTime += 3000;
                                  setTimeout(function(){
                                      notify(obj2[idx2].sunshade, '<?=$message?>');
                                      idx2++;
                                  }, delayTime);
                                  // isShownObj2 = true;
                              }
                          }

                          obj3 = jsonObject.twodayAvailable;
                          var idx3 = 0;
                          if (obj3 != undefined && obj3.length !== 0 && !isShownObj3) {
                            isShownObj3 = true;
                              <?php
                                  $message = Yii::t('messages', ' will be available 2 day later');
                              ?>
                              length += obj3.length;
                              for (var i = obj3.length - 1; i >= 0; i--) {
                                  $('.notification-item-template.hide .notification-username').html(obj3[i].sunshade);
                                  $('.notification-item-template.hide .notification-content').html('<?=$message?>');
                                  $('.notification-item-template.hide .notification-item-template a.lv-item').attr('data-notification-index', obj3[i].lookupId);
                                   $clone = $('.notification-item-template a').clone().removeClass('hide').attr('href', '<?=Url::to(["book-lookup/view"])?>' + '?sunshadeId=' + obj3[i].sunshadeId + '&bookId=' + obj3[i].bookId + '&guestId=' + obj3[i].guestId + '&lang=' + '<?=Yii::$app->language?>');
                                  $('.notification-body').append($clone);

                                  delayTime += 3000;

                                  setTimeout(function(){
                                      notify(obj3[idx3].sunshade, '<?=$message?>');
                                      idx3++;
                                  }, delayTime);

                                  // isShownObj3 = true;
                              }
                          }

                          obj4 = jsonObject.todayAvailable;
                          var idx4 = 0;
                          if (obj4 != undefined && obj4.length !== 0 && !isShownObj4) {
                            isShownObj4 = true;
                              <?php
                                  $message = Yii::t('messages', ' will be available today');
                              ?>
                              length += obj4.length;
                              for (var i = obj4.length - 1; i >= 0; i--) {
                                  $('.notification-item-template.hide .notification-username').html(obj4[i].sunshade);
                                  $('.notification-item-template.hide .notification-content').html('<?=$message?>');
                                  $('.notification-item-template.hide .notification-item-template a.lv-item').attr('data-notification-index', obj4[i].lookupId);
                                   $clone = $('.notification-item-template a').clone().removeClass('hide').attr('href', '<?=Url::to(["book-lookup/view"])?>' + '?sunshadeId=' + obj4[i].sunshadeId + '&bookId=' + obj4[i].bookId + '&guestId=' + obj4[i].guestId + '&lang=' + '<?=Yii::$app->language?>');
                                  $('.notification-body').append($clone);

                                  delayTime += 3000;

                                  setTimeout(function(){
                                      notify(obj4[idx4].sunshade, '<?=$message?>');
                                      idx4++;
                                  }, delayTime);

                                  /*isShownObj4 = true;*/
                              }
                          }

                          $('.notification-count').removeClass('hide').html(length);
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
  <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    
        <header id="header" class="clearfix bgm-white" data-current-skin="white">
            <ul class="header-inner">
                <li id="menu-trigger" data-trigger="#sidebar">
                    <div class="line-wrap">
                        <div class="line top"></div>
                        <div class="line center"></div>
                        <div class="line bottom"></div>
                    </div>
                </li>
            
                <li class="hidden-xs" >
                    <a href="<?= Url::to(['dashboard/index', 'lang' => $lang])?>" ><img class="p-b-10" src="<?=$baseurl?>/img/disponibile.png" alt="<?=Yii::t('messages', Yii::$app->name)?>" ></a>
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
                                <i class="tm-icon zmdi zmdi-notifications" style="color: #24c140;"></i>
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
                                          <img class="lv-img-sm img-responsive" src="<?=$baseurl?>/img/disponibile.png" alt="<?=Yii::$app->name?>">
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
              <li>
                <a class="bgm-orange" href="<?= Yii::$app->urlManagerFrontEnd->createUrl('/').'?lang='.Yii::$app->language?>" ><i class="fa fa-sign-in fa-2"></i> <?=Yii::t('messages', 'YOUR WEBSITE')?></a>
              </li>
              <li class="divider"></li>

              <?php $this->beginBlock('registeredUserBlock') ?>
                  <li><a href="<?=Url::to(['guest/sendbookinfo', 'lang' => $lang])?>"><i class="fa fa-print fa-2"></i> <?=Yii::t('messages', 'Bookings Info(ID/Email/Print)')?></a></li>
                  <li><a href="<?=Url::to(['guest/guestinfo', 'lang' => $lang])?>"><i class="zmdi zmdi-accounts-list zmdi-hc-fw"></i> <?=Yii::t('messages', 'Customers Info')?></a></li>
                  <li><a href="<?=Url::to(['guest/mapbooking', 'lang' => $lang])?>"><i class="zmdi zmdi-google-maps zmdi-hc-fw"></i> <?=Yii::t('messages', 'Map Booking')?></a></li>
                  <li><a href="<?=Url::to(['price/index', 'lang' => $lang])?>"><i class="fa fa-eur fa-2"></i> <?=Yii::t('messages', 'Price Management')?></a></li>
                  <li><a href="<?=Url::to(['setting/update', 'lang' => $lang])?>"><i class="zmdi zmdi-settings zmdi-hc-fw"></i> <?= Yii::t('messages', 'General Settings')?></a></li>
                   
                <?php $this->endBlock() ?>
                <?php if (!Yii::$app->user->isGuest) echo $this->blocks['registeredUserBlock']  ?>

                <?php $this->beginBlock('adminBlock') ?>
                   <li><a href="<?=Url::to(['user/index', 'lang' => $lang])?>"><i class="fa fa-user fa-2"></i> <?= Yii::t('messages', 'User Management')?></a></li>
                <?php $this->endBlock() ?>
                <?php if (Yii::$app->user->can('admin')) echo $this->blocks['adminBlock']  ?>

                <?php $this->beginBlock('guestBlock') ?>
                    <li><a href="<?=Url::to(['site/login', 'lang' => $lang])?>"><i class="fa fa-sign-in fa-2"></i> <?=Yii::t('messages', 'Login')?></a></li>
                    <li><a href="<?=Url::to(['site/signup', 'lang' => $lang])?>"><i class="fa fa-sign-in fa-2"></i> <?=Yii::t('messages', 'Signup')?></a></li>
                <?php $this->endBlock() ?>
                <?php if (Yii::$app->user->isGuest) echo $this->blocks['guestBlock']  ?>
                    
                <?php $this->beginBlock('nonGuestBlock') ?>
                    <li class="divider"></li>
                    <li><a href="<?=Url::to(['site/logout'])?>" data-method="post"><i class="fa fa-sign-out fa-2"></i> <?=Yii::t('messages', 'Logout')?></a></li>
                <?php $this->endBlock() ?>
                <?php if (!Yii::$app->user->isGuest) echo $this->blocks['nonGuestBlock']  ?>
                <li class="divider"></li>
                     
                <li><a href="<?php printFlags($this->context->paramKeys, $this->context->paramValues, $en)?>" class="<?php if($isEnglish) echo 'lang-active';?>"><img class="selectedflag" src="<?=$baseurl?>/img/flags/en.gif" alt="en"> <?=Yii::t('messages', 'English')?></a></li>
                <li><a href="<?php printFlags($this->context->paramKeys, $this->context->paramValues, $it)?>" class="<?php if(!$isEnglish) echo 'lang-active';?>""><img class="selectedflag" src="<?=$baseurl?>/img/flags/it.gif" alt="it"> <?=Yii::t('messages', 'Italian')?></a></li>
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
                <?php $this->beginBlock('registeredUserBlock') ?>
                    <li><a href="<?=Url::to(['guest/sendbookinfo', 'lang' => $lang])?>"><i class="fa fa-print fa-2"></i> <?=Yii::t('messages', 'Bookings Info(ID/Email/Print)')?></a></li>
                    <li><a href="<?=Url::to(['guest/guestinfo', 'lang' => $lang])?>"><i class="zmdi zmdi-accounts-list zmdi-hc-fw"></i> <?=Yii::t('messages', 'Customers Info')?></a></li>
                    <li><a href="<?=Url::to(['guest/mapbooking', 'lang' => $lang])?>"><i class="zmdi zmdi-google-maps zmdi-hc-fw"></i> <?=Yii::t('messages', 'Map Booking')?></a></li>
                    <li><a href="<?=Url::to(['price/index', 'lang' => $lang])?>"><i class="fa fa-eur fa-2"></i> <?=Yii::t('messages', 'Price Management')?></a></li>
                    <li><a href="<?=Url::to(['setting/update', 'lang' => $lang])?>"><i class="zmdi zmdi-settings zmdi-hc-fw"></i> <?= Yii::t('messages', 'General Settings')?></a></li>
                <?php $this->endBlock() ?>
                <?php if (Yii::$app->user->can('admin')) echo $this->blocks['registeredUserBlock']?>
                <?php $this->beginBlock('adminBlock') ?>
                    <li><a href="<?=Url::to(['user/index', 'lang' => $lang])?>"><i class="fa fa-user fa-2"></i> <?= Yii::t('messages', 'User Management')?></a></li>
                <?php $this->endBlock() ?>
                <?php if (!Yii::$app->user->isGuest) echo $this->blocks['registeredUserBlock']?>
                
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
<div class="modal fade" id="myModal-warning" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content overlay" style=" overflow-x: hidden;">
        <div class="row modal-body">
            <div class="col-xs-2 col-sm-2 nopl left">
                <img alt="<?=Yii::$app->name?>" src="<?=Yii::getAlias('@web')?>/img/disponibile.png" />
                <div class="number p-l-8">C23</div>
            </div>
            <div class="col-xs-9 col-sm-9 left ">
                <div class="m-b-10">
                    <span class="sunshade-array"></span>
                    <div class="message-warning"><?=Yii::t('messages', 'There is no book list yet.')?></div>
                    <button class="warning-button add_sunshine modal-close cursor-pointer"><?=Yii::t('messages', 'OK')?>
            </button>
                </div>
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
                <a href="<?=Url::to(['guest/gotocart', 'lang' => Yii::$app->language])?>" type="submit" class="btn-gotocart"><?=Yii::t('messages', 'GO TO CART')?>
                </a>
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
function printFlags($paramKeys, $paramValues, $language)
{
    $arrayUrl = array();

    $arrayUrl[0] = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;

    $arrayUrl[0] .= "?";
    foreach (Yii::$app->request->queryParams  as $key => $value) {
        if ($key != "lang") {
            $arrayUrl[0] .= $key . "=" . $value . "&";
        }
    }

    $arrayUrl[0] .= "lang=" . $language;
    for($i = 0; $i < count($paramKeys); $i++)
        if($paramKeys[$i] !== 'lang')
            $arrayUrl[$paramKeys[$i]] = $paramValues[$i];

    return print_r(Url::to([$arrayUrl[0]]));
}
?>



