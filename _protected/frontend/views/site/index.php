<?php

/* @var $this yii\web\View */
$this->title = Yii::t('app', Yii::$app->name);
$lang = Yii::$app->language;

?>
     
<div class="site-index">
    <div class="row" style= "margin-top: 10px;">
        <div class="col-sm-4 col-md-4 newfont">
            <form role="search" action="" method="post">
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                <input class="span2 date " id="appendedInputButton" type="text" style=" width: 200px;" placeholder="<?=Yii::t('messages', 'Check Availability')?>">
                <button  type="submit" class="check-availability" >
                    <i class="fa fa-search"></i>
                </button >
            </form>
        </div>
        <div class="col-sm-8 col-md-8 lead newfont" style="color:white">
            <?=Yii::t('messages', 'Please click on the available sunshade to book it')?>
            <strong style="color: darkgreen;"> (<?=Yii::t('messages', 'green')?>)</strong>
        </div>
    </div>
            
    <div id="imgdiv" align="center" style="margin-top: 10px">
        <img  id="image" src="<?=Yii::getAlias('@web')?>/images/1.png" />
    </div>

    <div class="video__background">
        <p class="lead text-center newfont" style="color:white">  <?=Yii::t('messages', 'Welcome to Beach Club Ippocampo')?> </p>
        <video  autoplay loop muted preload="auto" width=100%>
          <source src="<?=Yii::getAlias('@web')?>/uploads/beach.mp4" type="video/mp4">
        </video>
       
    </div>
</div>

<script type="text/javascript">
          
    tagWidth = 18;
    tagFontSize = 6;
    tagHeight = 18;
    dy = 6;
    dx = 1;
    lang = '<?=$lang?>';

    seat = '';
    bookingChartUrl = '';

    function bookingChart() {
        if (seat == '')
        {
            $.notifyDefaults({
            placement: {
                from:'top',
                align:'center'
            }});
            $.notify({
                message: '<?=Yii::t('messages', 'There is no book list yet.')?>'
            });
            return;
        }

        bookingChartUrl = '<?= Yii::$app->urlManager->createUrl(["site/sunshade"]) ?>';
        bookingChartUrl += '?id=' + seat;
        bookingChartUrl += '&lang=' + '<?=Yii::$app->language?>';
        bookingChartUrl += '&day=' + $('.span2.date').val();
        window.location.href = bookingChartUrl;
    }

    $('body').on('click', '.booklist', function(e){
        e.preventDefault();
        bookingChart();                
    });

    var $img;
    ;(function($) {
        $(document).ready(function() {
             function checkPosition() {
                if (window.matchMedia('(max-width: 767px)').matches) {
                    tagWidth = 8;
                    tagFontSize = 3;
                    tagHeight = 8;
                    dy = 6;
                    dx = 4;
                } 
            }    

            var currYear = new Date().getFullYear();
            var startDate = new Date(currYear,5,1);
            var endDate = new Date(currYear, 8, 8);

            $day = '<?=$day?>';
            $('.span2.date').datepicker({
                daysOfWeekHighlighted: "0",
                autoclose: true,
                todayHighlight: true,
                format: "yyyy-mm-dd",
                startDate: startDate,
                endDate: endDate,
                orientation: "auto top",
                keyboardNavigation: true,
                setDate: $day,
            }).on('changeDate', function(ev){
                $('form').attr('action', '<?=Yii::$app->urlManager->createUrl(['site/index'])?>' + "?day=" + ev.format());
            });

            $('.span2.date').datepicker('setDate', $day);

            function addMarker(target, coord){
                $img = target.imgNotes({
                    onShow: $.noop,
                    onAdd: function() {
                        this.options.vAll = "middle";
                        this.options.hAll = "middle";
                        this.options.tagHeight = tagHeight;
                        this.options.tagWidth = tagWidth;
                        this.options.tagFontSize = tagFontSize;
                        var elem = $(document.createElement('span')).addClass("marker blue").css({
                            // "background-color": "#0f0",
                            height: tagHeight+"px",
                            width: tagWidth + "px",
                          //  "border-radius": "60px",
                   
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



