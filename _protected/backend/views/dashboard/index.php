<?php
    use yii\helpers\Url;

	$this->title = Yii::t('messages', "Dashboard");
    $baseurl = Yii::getAlias("@web")."/..";
?>

<script type="text/javascript">
    var Revenue = <?=$Revenue?>;
    var Customers = <?=$Customers?>;
    var from = '<?=$from?>';
    var to = '<?=$to?>';
    $(document).ready(function(){
        $('#daily-customers').highcharts({
            global: {
                useUTC: true,
                getTimezoneOffset: function (timestamp) {
                    return -60;
                }
            },
            credits: {
                enabled: false
            },
            chart: {
                zoomType: 'x'
            },
            title: {
                text: '<?=Yii::t("messages", "Daily customers")?>'
            },
            subtitle: {
                text: document.ontouchstart === undefined ?
                        '<?=Yii::t("messages", "Click and drag in the plot area to zoom in")?>' : '<?=Yii::t("messages", "Pinch the chart to zoom in")?>'
            },
            xAxis: {
                type: 'datetime'
            },
            yAxis: {
                title: {
                    text: '<?=Yii::t("messages", "Number of Customers")?>'
                },
                min: 0
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    turboThreshold:0,
                },
                area: {
                    fillColor: {
                        linearGradient: {
                            x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: 1
                        },
                        stops: [
                            [0, Highcharts.getOptions().colors[0]],
                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                        ]
                    },
                    marker: {
                        radius: 2
                    },
                    lineWidth: 1,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null,
                }
            },

            series: [{
                type: 'area',
                name: '<?=Yii::t("messages", "Daily Customers")?>',
                data: [<?=$dailyCustomers?>]
            }]
        });
    });
</script>



	<div class="block-header">
    <h2 class="c-white"><?=Yii::t('messages', 'Dashboard')?></h2>
    <div class="row m-t-10">
        <form id="chart-search-form" data-toggle="validator" role="form">
            <div class="col-sm-4 col-xs-12">
                <div class="form-group group-from">
                    <div class="c-black"><?=Yii::t('messages', 'Check In')?></div>
                    <div class="input-group date">
                        <span class="input-group-addon round bgm-gray"><i class="fa fa-calendar"></i></span>
                        <input type="text" name="from"  class="form-control chart-from p-l-5" data-error="This field cannot be blank" required readonly style="font-size: 12px;">
                    </div>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="form-group group-to">
                    <div class="c-black"><?=Yii::t('messages', 'Check Out')?></div>
                    <div class="input-group date">
                        <span class="input-group-addon round bgm-gray"><i class="fa fa-calendar"></i></span>
                        <div class="dtp-container fg-line">
                            <input type="text" name="to" id="search-to"  class="form-control chart-to p-l-5" data-error="This field cannot be blank" required readonly style="font-size: 12px;">
                        </div>
                    </div>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-sm-2 col-xs-12 m-t-15">
                <input type="hidden" name="lang" value="<?=Yii::$app->language?>">
                <button type="submit" class="btn btn-success btn-chart-search"><span class="glyphicon glyphicon-search"></span> <?=Yii::t('messages', 'Search')?></button>
            </div>
        </form>
    </div>
    
    <ul class="actions">
        <li>
            <a href="">
                <i class="zmdi zmdi-refresh-alt c-white"></i>
            </a>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-padding">
            <div class="card-body card-padding">
                <div id="daily-customers" class="flot-chart" style="min-width: 280px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-sm-6">
        <div class="card">
            <div class="card-header">
                <h2><?=Yii::t('messages', 'Total Revenue')?> &nbsp; (&euro; <?=$totalRevenue?>)</h2>
            </div>
            
            <div class="card-body card-padding">
                <div id="donut-chart" class="flot-chart-pie"></div>
                <div class="flc-donut hidden-xs"></div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-sm-6">
        <div class="card">
            <div class="card-header">
                <h2><?=Yii::t('messages', 'Total Customers')?> &nbsp; (<?=$totalCustomers?>)</h2>
            </div>
            
            <div class="card-body card-padding">
                <div id="pie-chart" class="flot-chart-pie"></div>
                <div class="flc-pie hidden-xs"></div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-6">
            <div id="pie-charts" class="dash-widget-item">
                <div class="bgm-pink">
                    <div class="dash-widget-header">
                        <div class="dash-widget-title"><?=Yii::t('messages', "Sunshades Statistics")?></div>
                    </div>
                    
                    <div class="clearfix"></div>
                    
                    <div class="text-center p-20 m-t-25">
                        <div class="easy-pie main-pie" data-percent="<?=intval(intval($totalBooked) / (35*5) * 100)?>">
                            <div class="percent"><?=intval(intval($totalBooked) / (35*5) * 100)?></div>
                            <div class="pie-title" style="top: 148px; padding-bottom:10px"><?php echo Yii::t('messages', "Toltal Number of Booked Sunshades") . " (" . $totalBooked .")"?></div>
                        </div>
                    </div>
                </div>
                
                <div class="p-t-20 p-b-20 text-center">
                    <div class="easy-pie sub-pie-1" data-percent="<?=intval(intval($Booked['A']) / 35 * 100)?>">
                        <div class="percent"><?=intval(intval($Booked['A']) / 35 * 100)?></div>
                        <div class="pie-title" style="top: 95px;"><?=Yii::t('messages', "Sunshade")?> &nbsp;A(<?=$Booked['A']?>)</div>
                    </div>
                    <div class="easy-pie sub-pie-2" data-percent="<?=intval(intval($Booked['B']) / 35 * 100)?>">
                        <div class="percent"><?=intval(intval($Booked['B']) / 35 * 100)?></div>
                        <div class="pie-title" style="top: 95px;"><?=Yii::t('messages', "Sunshade")?> &nbsp;B(<?=$Booked['B']?>)</div>
                    </div>
                    <div class="easy-pie sub-pie-1" data-percent="<?=intval(intval($Booked['C']) / 35 * 100)?>">
                        <div class="percent"><?=intval(intval($Booked['C']) / 35 * 100)?></div>
                        <div class="pie-title" style="top: 95px;"><?=Yii::t('messages', "Sunshade")?> &nbsp;C(<?=$Booked['C']?>)</div>
                    </div>
                    <div class="easy-pie sub-pie-2" data-percent="<?=intval(intval($Booked['D']) / 35 * 100)?>">
                        <div class="percent"><?=intval(intval($Booked['D']) / 35 * 100)?></div>
                        <div class="pie-title" style="top: 95px;"><?=Yii::t('messages', "Sunshade")?> &nbsp;D(<?=$Booked['D']?>)</div>
                    </div>
                    <div class="easy-pie sub-pie-1" data-percent="<?=intval(intval($Booked['E']) / 35 * 100)?>">
                        <div class="percent"><?=intval(intval($Booked['E']) / 35 * 100)?></div>
                        <div class="pie-title" style="top: 95px;"><?=Yii::t('messages', "Sunshade")?> &nbsp;E(<?=$Booked['E']?>)</div>
                    </div>
                </div>

            </div>
    </div>
    
    <div class="col-md-6 col-sm-6">
        <div id="best-selling" class="dash-widget-item">
            <div class="dash-widget-header">
                <div class="dash-widget-title"><?=Yii::t('messages', "Best Customers")?></div>
                <img src="<?=Yii::getAlias('@web')?>/img/profile-menu.png" alt="">
                <?php if(count($bestCustomers) > 0) { ?>
                <a class="lv-item" href="<?=Url::to(['/guest/guestdetail', 'id'=>$bestCustomers[0]['guestId'], 'lang' => Yii::$app->language])?>">
                    <div class="main-item">
                            <small class="top-customer"><?=$bestCustomers[0]['guestname']?> &nbsp;&nbsp; (<?=$bestCustomers[0]['recurringcount']?>)</small>
                            <h2>&euro;<?=$bestCustomers[0]['totalprice']?></h2>
                <?php }?>
                    </div>
                </a>
            </div> 
            <div class="listview p-t-5">
                <?php for ($i = 1; $i < count($bestCustomers); $i++) {?>
                      <a class="lv-item" href="<?=Url::to(['/guest/guestdetail', 'id'=>$bestCustomers[$i]['guestId'], 'lang' => Yii::$app->language])?>">
                        <div class="media">
                            <div class="pull-left">
                                <img class="lv-img-sm" src="<?=$baseurl?>/img/logo.png" alt="">
                            </div>
                            <div class="media-body">
                                <div class="lv-title"><?=$bestCustomers[$i]['guestname']?>&nbsp;&nbsp;(<?=$bestCustomers[$i]['recurringcount']?>)</div>
                                <small class="lv-small">&euro;<?=$bestCustomers[$i]['totalprice']?></small>
                            </div>
                        </div>
                    </a>
               <?php }?>
               <?php if (count($bestCustomers)): ?>
                   <a class="lv-footer" href="<?=Url::to(['guest/sendbookinfo', 'lang'=>Yii::$app->language])?>">
                    <?=Yii::t('messages', 'View All')?>
                </a>
               <?php endif ?>
            </div>
        </div>
    </div>    
</div>
