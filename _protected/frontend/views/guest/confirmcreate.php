<?php

use yii\helpers\Html;

//require_once '../views/guest/paypalcredit/header.php';

/* @var $this yii\web\View */
/* @var $model frontend\models\Guest */

$this->params['breadcrumbs'][] = ['label' => Yii::t('messages', 'Guests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

;
?>
<div class="guest-create">
    
    <div class="row">
        <div class="well col-xs-6">
		<font size=2 color=black face=Verdana><b>SetExpressCheckout - digital
				goods</b> </font> <br> <br>
                                <form method="POST" action="DGsetExpressCheckout.php">

                        <table class="table-responsive">
                                <tr>
                                        <td align=right></td>
                                        <td align=left>Digital Download</td>
                                </tr>

                                <tr>
                                        <td align=right>Currency:</td>
                                        <td align=left><input type=text size=30 maxlength=32
                                                name=currencyId value=USD></td>
                                </tr>
                                <tr>
                                        <td align=right>Amount:</td>
                                        <td align=left><input type=text size=30 maxlength=32 name=amount
                                                value=1.00></td>
                                </tr>
                                <tr>
                                        <td />
                                        <td align=left><b></b></td>
                                </tr>

                                <tr>
                                        <td />
                                        <td><input type="image"
                                                src='https://www.paypal.com/en_US/i/btn/btn_dg_pay_w_paypal.gif'
                                                id="submitBtn" name="submitBtn" value="Pay with PayPal" /></td>
                                </tr>


                        </table>
			<script type="text/javascript" src="js/dg.js"></script>
			<script>
                            var dg = new PAYPAL.apps.DGFlow({
                                trigger: "submitBtn"
                            });
                        </script>
		</form>
        </div>
        
        <div class="col-xs-1"></div>
        
        <div class="well col-xs-4">
            <h3><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form_confirm', [
                'model' => $model,
                'email' => $email,
                'fullname' => $fullname,
            ]) ?>
            
            
        </div>
    </div>
</div>
