<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

$this->title = Yii::t('messages', 'Contact Us');
?>

<script type="text/javascript">
    ;(function($) {
        $(document).ready(function() {
            $('.booklist').css('display', 'none');

            $('.btn-contact').click(function(e){
                e.preventDefault();
                if ($('input[type="checkbox"]').prop("checked") == false) {
                    $.notify({
                        message: '<?=Yii::t("messages", "Please confirm terms & conditions")?>'
                    },{
                        type: 'danger',
                        allow_dismiss: true,    
                        delay: 10000000000,
                    });
                    return;
                }
                $('form').submit();
            });
        });
    })(jQuery);
</script>

<style type="text/css">
    .help-block {
            font-size: 13px;
    }
</style>
<div class="site-contact">
    <div class="card m-15">
        <div class="card-header card-padding bgm-gray c-white">
            <div class="f-20 text-uppercase"><?= Html::encode($this->title) ?></div>
            <p class="f-13">
                <?= Yii::t('messages', 'If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.'); ?>
            </p>
        </div>
        <div class="card-body card-padding">
            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                <?= $form->field($model, 'name') ?>
                <?= $form->field($model, 'email') ?>
                <?= $form->field($model, 'subject') ?>
                <?= $form->field($model, 'body')->textArea(['rows' => 6]) ?>
                <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-lg-1" style="    margin-right: 20px;">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>
                <div class="form-group field-contactform-privacypolicy required has-success">
                    <div class="checkbox i-checks">
                        <label for="contactform-privacypolicy">
                        <input type="hidden" name="ContactForm[privacyPolicy]" value="0">
                        <input type="checkbox" id="contactform-privacypolicy" name="ContactForm[privacyPolicy]" value="1"><a href="//www.iubenda.com/privacy-policy/<?php if(Yii::$app->language == 'en') echo '7924689';  else echo '7924688';?>" class="iubenda-nostyle no-brand iubenda-embed" title="Privacy Policy"><?=Yii::t('messages', 'Accept Privacy Disclaimer')?></a><script type="text/javascript">(function (w,d) {var loader = function () {var s = d.createElement("script"), tag = d.getElementsByTagName("script")[0]; s.src = "//cdn.iubenda.com/iubenda.js"; tag.parentNode.insertBefore(s,tag);}; if(w.addEventListener){w.addEventListener("load", loader, false);}else if(w.attachEvent){w.attachEvent("onload", loader);}else{w.onload = loader;}})(window, document);</script></label>

                    </div>
                </div>
              

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('messages', 'Submit'), ['class' => 'btn btn-primary btn-contact', 'name' => 'contact-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
