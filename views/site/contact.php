<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Contactanos';
$this->params['breadcrumbs'][] = $this->title;
\app\assets\AppAsset::register($this);
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

        <div class="alert alert-success">
           Tu mensaje ha sido enviado exitosamente. <br>
            No dudes en contactar con nosotros. Estamos aqui para ti!!!!!!!
        </div>

        <p>
            <?php if (Yii::$app->mailer->useFileTransport): ?>
                Because the application is in development mode, the email is not sent but saved as
                a file under <code><?= Yii::getAlias(Yii::$app->mailer->fileTransportPath) ?></code>.
                Please configure the <code>useFileTransport</code> property of the <code>mail</code>
                application component to be false to enable email sending.
            <?php endif; ?>
        </p>

    <?php else: ?>

        <p>
            Si tienes algun problema o alguna pregunta escribenos, te responderemos inmediatamente!!!!
        </p>

        <div class="row">
            <div class="col-lg-5">

                <?php $form = ActiveForm::begin(['id' => 'contact-form','options' => ['clientValidation'=>true]]); ?>

                    <?=Yii::$app->user->isGuest? $form->field($model, 'name')->textInput(['autofocus' => true]):'' ?>

                    <?= Yii::$app->user->isGuest?$form->field($model, 'email'):'' ?>

                    <?= $form->field($model, 'subject') ?>

                    <?= $form->field($model, 'body')->textArea(['rows' => 6]) ?>

                    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                        'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                    ]) ?>

                    <div class="form-group">
                       <?= Html::submitButton(' <span class="glyphicon glyphicon-send"></span>&nbsp;Enviar', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    <?php endif; ?>
</div>
