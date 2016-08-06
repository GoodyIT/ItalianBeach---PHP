<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model frontend\models\Guest */
$lang = Yii::$app->language;
if ($lang == "") $lang = 'en';

$this->title = Yii::t('messages', 'Send Book Info');
$this->params['breadcrumbs'][] = ['label' => Yii::t('messages', 'Customers Info'), 'url' => ['guest/sendbookinfo', 'lang' => $lang]];
$this->params['breadcrumbs'][] = Yii::t('messages', 'Customer Detail');

$baseurl = Yii::getAlias("@appRoot") //"http://www.beachclubippocampo.rentals";
?>

<script type="text/javascript" src="<?=$baseurl?>/libs/jquery.fs.zoetrope.min.js"></script>
<script type="text/javascript" src="<?=$baseurl?>/js/toe.min.js"></script>
<script type="text/javascript" src="<?=$baseurl?>/js/jquery.mousewheel.min.js"></script>
<script type="text/javascript" src="<?=$baseurl?>/js/imgViewer.min.js"></script>
<script type="text/javascript" src="<?=$baseurl?>/src/imgNotes.js"></script>
 <script type="text/javascript" src="<?=$baseurl?>/js/bootstrap-notify.min.js"></script>  

 <script type="text/javascript" src="<?=$baseurl?>/js/jQuery.print.js"></script>
 <link rel="stylesheet" href="<?=$baseurl?>/css/demostyles.css" media="screen">

<div class="guest-detail">
	<div id="imgdiv" align="center" >
        <img id="image" src="<?=$baseurl?>/images/1.png" style="padding:20px;" width="100%" height="100%"/>
    </div>

    <div class="page-header">
        <span class="sendbookinfo pull-left btn btn-success" style="margin-right: 10px"> 
         <?=Yii::t('messages', 'Send Via Email')?>
        </span>

        <span class="createPDF pull-left btn btn-success" style="margin-right: 10px"> 
            <?=Yii::t('messages', 'Create PDF for Book Info')?>
        </span> 
        <span class="print pull-left btn btn-success"> 
            <?=Yii::t('messages', 'Print Book Info')?>
        </span>
        <div class="clearfix"></div>
    </div>
   <div class="card">
        <div class="card-header">
            <h2></h2>
        </div>
        <div class="table-responsive">
        <table id="data-table-command" class="table table-striped table-vmiddle">
            <thead>
                <tr>
                    <th data-column-id="sunshadeId" data-type="numeric" data-visible="false">sunshadeId</th>
                    <th data-column-id="id" data-type="numeric">ID</th>
                    <th data-column-id="username"><?=Yii::t('messages', 'Username')?></th>
                    <th data-column-id="address"><?=Yii::t('messages', 'Address')?></th>
                    <th data-column-id="email"><?=Yii::t('messages', 'Email')?></th>
                    <th data-column-id="phonenumber"><?=Yii::t('messages', 'Phonenumber')?></th>
                    <th data-column-id="country"><?=Yii::t('messages', 'Country')?></th>
                    <th data-column-id="arrival"><?=Yii::t('messages', 'Arrival Date')?></th>
                    <th data-column-id="checkout"><?=Yii::t('messages', 'Checkout Date')?></th>
                    <th data-column-id="sunshade"><?=Yii::t('messages', 'Sunshade')?></th>
                    <th data-column-id="paidprice"><?=Yii::t('messages', 'Paid / Total')?>&nbsp;(€)</th>
                    <th data-column-id="commands" data-formatter="commands" data-sortable="false"></th>
                </tr>
            </thead>
            <tbody>
            <?php for ($i = 0; $i < count($guest); $i++) {?>
                <tr>
                    <td><?=$guest[$i]['Id']?></td>
                    <td><?=$i+1?></td>
                    <td><?=$guest[$i]['username']?></td>
                    <td><?=$guest[$i]['address']?></td>
                    <td><?=$guest[$i]['email']?></td>
                    <td><?=$guest[$i]['phonenumber']?></td>
                    <td><?=$guest[$i]['country']?></td>
                    <td><?=$guest[$i]['arrival']?></td>
                    <td><?=$guest[$i]['checkout']?></td>
                    <td><?=$guest[$i]['sunshade']?></td>
                    <td><?=$guest[$i]['paidprice']?>/<?=$guest[$i]['price']?></td>
                </tr>
                <?php }?>
            </tbody>
        </table>
        </div>
    </div>
    <form id="guest-form" method="post" action="<?= Yii::$app->urlManager->createUrl(["guest/sendbulkemail"]) ?>">
        <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
        <input type="hidden" name="id" value= "<?=$id?>" /> 
        <input type="hidden" name="lang" value= "<?=Yii::$app->language?>" /> 
    </form>
</div>

<script type="text/javascript">
          
    tagWidth = 18;
    tagFontSize = 6;
    tagHeight = 18;
    lang = '<?=$lang?>';
    dy = 6;
    dx = 1;

    seat = '';

    var $img;
    ;(function($) {
        $(document).ready(function() {
       
            var grid = $("#data-table-command").bootgrid({
            caseSensitive: false,
            css: {
                icon: 'zmdi icon',
                iconColumns: 'zmdi-view-module',
                iconDown: 'zmdi-expand-more',
                iconRefresh: 'zmdi-refresh',
                iconUp: 'zmdi-expand-less'
            },
             formatters: {
                commands: function (column, row)
                {
                    return "<button type=\"button\" class=\"btn btn-icon command-view waves-effect waves-circle\" data-row-id=\"" + row.sunshadeId + "\"><span class=\"zmdi zmdi-view-toc zmdi-hc-fw\"></span></button> ";
                }
            },
            rowSelect: true,
            selection: true,
        }).
        on("loaded.rs.jquery.bootgrid", function(e){
            grid.find(".command-view").on("click", function(e)
            {
                 var id = $(this).data('row-id');

                if(id != undefined)
                    location.href = '<?=Url::to(["bookinfo/view"])?>' + '/' + id +'?lang='  + '<?=Yii::$app->language?>';
           });
        });

       
        	$('.sendbookinfo').click(function() {
	            $('#guest-form').attr('action', '<?= Yii::$app->urlManager->createUrl(["guest/sendbulkemail"]) ?>')
	            $('#guest-form').submit();
	        });

	        $('.createPDF').click(function() {
	            window.location.href = '<?= Yii::$app->urlManager->createUrl(["guest/pdfall"]) ?>?id=<?=$id?>';
	        });

	        $('.print').click(function() {
	             $.print("#data-table-command");
	            
	        });

        	function checkPosition() {
                if (window.matchMedia('(max-width: 767px)').matches) {
                    tagWidth = 8;
                    tagFontSize = 3;
                    tagHeight = 8;
                    dy = 4;
                    dx = 1;
                } else {
                }
            }    

            function addMarker(target, coord){
                $img = target.imgNotes({
                    onAdd: function() {
                        this.options.vAll = "middle";
                        this.options.hAll = "middle";
                        this.options.tagHeight = tagHeight;
                        this.options.tagWidth = tagWidth;
                        this.options.tagFontSize = tagFontSize;
                        var elem = $(document.createElement('span')).addClass("marker blue").css({
                            height: tagHeight+"px",
                            width: tagWidth + "px",
                        }).attr("rel", "tooltip");
                        return elem;
                    }
                });

                $img.imgNotes("import", coord);
            }

            checkPosition();
            addMarker($('#imgdiv').children('img'), <?= $jsonValue ?>);

            var targets = $( '[rel~=tooltip]' ),
                target  = false,
                tooltip = false,
                title   = false;

            targets.bind( 'mouseenter', function()
            {
                target  = $( this );
                tip     = target.attr( 'title' );
                tooltip = $( '<div id="tooltip"></div>' );

                if( !tip || tip == '' )
                    return false;

                target.removeAttr( 'title' );
                tooltip.css( 'opacity', 0 )
                    .html( tip )
                    .appendTo( 'body' );

                var init_tooltip = function()
                {
                    if( $( window ).width() < tooltip.outerWidth() * 1.5 )
                        tooltip.css( 'max-width', $( window ).width() / 2 );
                    else
                        tooltip.css( 'max-width', 340 );

                    var pos_left = target.offset().left + ( target.outerWidth() / 2 ) - ( tooltip.outerWidth() / 2 ),
                        pos_top  = target.offset().top - tooltip.outerHeight() - 20;

                    if( pos_left < 0 )
                    {
                        pos_left = target.offset().left + target.outerWidth() / 2 - 20;
                        tooltip.addClass( 'left' );
                    }
                    else
                        tooltip.removeClass( 'left' );

                    if( pos_left + tooltip.outerWidth() > $( window ).width() )
                    {
                        pos_left = target.offset().left - tooltip.outerWidth() + target.outerWidth() / 2 + 20;
                        tooltip.addClass( 'right' );
                    }
                    else
                        tooltip.removeClass( 'right' );

                    if( pos_top < 0 )
                    {
                        var pos_top  = target.offset().top + target.outerHeight();
                        tooltip.addClass( 'top' );
                    }
                    else
                        tooltip.removeClass( 'top' );

                    tooltip.css( { left: pos_left, top: pos_top } )
                        .animate( { top: '+=10', opacity: 1 }, 50 );
                };

                init_tooltip();
                $( window ).resize( init_tooltip );

                var remove_tooltip = function()
                {
                    tooltip.animate( { top: '-=10', opacity: 0 }, 50, function()
                    {
                        $( this ).remove();
                    });

                    target.attr( 'title', tip );
                };

                target.bind( 'mouseleave', remove_tooltip );
                tooltip.bind( 'click', remove_tooltip );
            });
        });
    })(jQuery);
</script>