<?php

use yii\helpers\Html;
use yii\helpers\Url;

$baseurl = Yii::getAlias("@appRoot");//"http://www.beachclubippocampo.rentals";
?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title>Beachclubippocampo</title>

        <link rel="apple-touch-icon" sizes="180x180" href="<?=$baseurl?>/images/favicon/apple-touch-icon.png">
	    <link rel="icon" type="image/png" href="<?=$baseurl?>/images/favicon/favicon-32x32.png" sizes="32x32">
	    <link rel="icon" type="image/png" href="<?=$baseurl?>/images/favicon/favicon-16x16.png" sizes="16x16">
	    <link rel="manifest" href="<?=$baseurl?>/images/favicon/manifest.json">
	    <link rel="mask-icon" href="<?=$baseurl?>/images/favicon/safari-pinned-tab.svg" color="#5bbad5">
	    <meta name="theme-color" content="#ffffff">
        
        <!-- Vendor CSS -->
        <link href="<?=$baseurl?>/js/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?=$baseurl?>/js/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
            
        <!-- CSS -->
        <link href="<?=Yii::getAlias('@web')?>/css/app.min.1.css" rel="stylesheet">
        <link href="<?=Yii::getAlias('@web')?>/css/app.min.2.css" rel="stylesheet">
    </head>
    
<body class="four-zero-content">  
	<div class="four-zero">
	    <h2>Opps</h2>
	    <br/>
	    <small><?php echo Yii::t('messages', 'There is no price table for '); 
	    		if($seat[0] == 1) echo Yii::t('messages', 'Room');
	    		else echo Yii::t('messages', 'Sunshade');
	    		echo " " . $seat;?>  
	    </small>
	    
	    <footer>
	        <a href="<?=Url::to(['price/index', 'lang' => Yii::$app->language])?>"><i class="zmdi zmdi-arrow-back"></i></a>
	        <a href="<?=Url::to(['dashboard/index', 'lang' => Yii::$app->language])?>"><i class="zmdi zmdi-home"></i></a>
	    </footer>
	</div>
</body>
</html>