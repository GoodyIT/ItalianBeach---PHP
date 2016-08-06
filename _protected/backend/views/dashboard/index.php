<?php
    use yii\helpers\Url;

	$this->title = Yii::t('messages', "Dashboard");
    
    $baseurl = Yii::getAlias("@appRoot");//"http://www.beachclubippocampo.rentals";
?>

    <script type="text/javascript">
        $(document).ready(function(){
            var Revenue = <?=$Revenue?>;
            var Customers = <?=$Customers?>;
           
    /* Donut Chart */

        if($('#donut-chart')[0]){
            $.plot('#donut-chart', Revenue, {
                series: {
                    pie: {
                        innerRadius: 0.5,
                        show: true,
                        stroke: { 
                            width: 2,
                        },
                         label :{
                            show: true,
                        },
                    },
                },
                legend: {
                    container: '.flc-donut',
                    backgroundOpacity: 0.5,
                    noColumns: 0,
                    backgroundColor: "white",
                    lineWidth: 0
                },
                grid: {
                    hoverable: true,
                    clickable: true
                },
                tooltip: true,
                tooltipOpts: {
                    content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
                    shifts: {
                        x: 20,
                        y: 0
                    },
                    defaultTheme: false,
                    cssClass: 'flot-tooltip'
                }
                
            });
        }

     if($('#pie-chart')[0]){
            $.plot('#pie-chart', Customers, {
                series: {
                    pie: {
                        show: true,
                        stroke: { 
                            width: 2,
                        },
                        label :{
                            show: true,
                        },
                    },
                },
                legend: {
                    container: '.flc-pie',
                    backgroundOpacity: 0.5,
                    noColumns: 0,
                    backgroundColor: "white",
                    lineWidth: 0
                },
                grid: {
                    hoverable: true,
                    clickable: true
                },
                tooltip: true,
                tooltipOpts: {
                    content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
                    shifts: {
                        x: 20,
                        y: 0
                    },
                    defaultTheme: false,
                    cssClass: 'flot-tooltip'
                }
                
            });
        }
    });
    </script>

	<script src="<?=$baseurl?>/js/vendors/sparklines/jquery.sparkline.min.js"></script>
    <script src="<?=$baseurl?>/js/vendors/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
    <script src="<?=$baseurl?>/js/vendors/bower_components/flot/jquery.flot.js"></script>
    <script src="<?=$baseurl?>/js/vendors/bower_components/flot/jquery.flot.time.js"></script>
    <script src="<?=$baseurl?>/js/vendors/bower_components/flot/jquery.flot.resize.js"></script>
    <script src="<?=$baseurl?>/js/vendors/bower_components/flot/jquery.flot.pie.js"></script>
    <script src="<?=$baseurl?>/js/vendors/bower_components/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>

 	<div class="block-header">
        <h2><?=Yii::t('messages', 'Dashboard')?></h2>
        
        <ul class="actions">
            <li>
                <a href="">
                    <i class="zmdi zmdi-refresh-alt"></i>
                </a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div id="daily-customers" class="flot-chart" style="min-width: 310px;"></div>
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
                                    <img class="lv-img-sm" src="<?=$baseurl?>/images/logo.png" alt="">
                                </div>
                                <div class="media-body">
                                    <div class="lv-title"><?=$bestCustomers[$i]['guestname']?>&nbsp;&nbsp;(<?=$bestCustomers[$i]['recurringcount']?>)</div>
                                    <small class="lv-small">&euro;<?=$bestCustomers[$i]['totalprice']?></small>
                                </div>
                            </div>
                        </a>
                   <?php }?>
                   <?php if (count($bestCustomers)): ?>
                       <a class="lv-footer" href="<?=Url::to(['/bookinfo/index', 'lang'=>Yii::$app->language])?>">
                        <?=Yii::t('messages', 'View All')?>
                    </a>
                   <?php endif ?>
                </div>
            </div>
        </div>    
    </div>

    <script type="text/javascript">
    	$(function () { 
              // $.plot("#daily-customers", [<?=$dailyCustomers?>], {
              //       yaxis: {
              //           tickFormatter: function (val, axis) {
              //               return Math.ceil(val) + " miles";
              //           },    
              //       },
              //       xaxis: {
              //           mode: "time"
              //       }
              //   });
		    // $.getJSON('https://www.highcharts.com/samples/data/jsonp.php?filename=usdeur.json&callback=?', function (data) {
            $('#daily-customers').highcharts({
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
                        threshold: null
                    }
                },

                series: [{
                    type: 'area',
                    name: '<?=Yii::t("messages", "Daily Customers")?>',
                    data: [<?=$dailyCustomers?>]
                }]
            });
        // });



	});

    </script>