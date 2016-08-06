<?php
/**
 * Created by PhpStorm.
 * User: aaaa
 * Date: 5/11/2016
 * Time: 10:29 AM
 */

?>
<script>
    screenWidth = $(window).width();
    screenHeight = $(window).height();

    originalWidth = 1156;
    originalHeight = 1636;
    imgWidth = screenWidth;
    imgHeight = originalHeight * screenWidth/originalWidth;
    $(window).on("orientationchange",function(event){
        if (event.orientation == 'landscape') {
            imgWidth = originalWidth / 2;
            imgHeight = originalHeight / 2;
        }

    });

    ;(function($) {
    $(function () {

        // control the menu item
        $( "nav ul li" ).slice( 0, 4 ).removeClass('active');
        $( "li.waves-effect:nth-child(4)").addClass('active');
      });
})(jQuery);
</script>
<style>
    .info-image {

    }
</style>

<div class="site-index">
    <div class="center-block" style="text-align: center;">
        <img src="<?=Yii::getAlias('@web')?>/images/info.png"  style=" width:" + width + "; height:" + height/>
    </div>
</div>