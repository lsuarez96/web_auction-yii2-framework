<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


AppAsset::register($this);
$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login " style="align-content: center; align-items: center; padding-top: 25px;">

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-10\">{input}</div>\n<div class=\"col-lg-offset-2 col-lg-2\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label', 'style' => 'text-align: right; '],
        ],
    ]); ?>
    <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => 'Nombre de usuario o email']) ?>

    <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Contraseña']) ?>

    <?= $form->field($model, 'rememberMe')->checkbox(['template' => "{error}\n<div class=\"col-lg-offset-2 col-lg-10 hidden\">{input}{label}</div>"]) ?>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <?= Html::submitButton('Login', ['class' => 'btn-submit btn-lg btn-block', 'name' => 'login-button']) ?>
            <span style="color: #666666">
                    Aun no tienes cuenta?
                    <a href="index.php?r=usuario%2Fcreate">Registrarse</a>
                </span>
            <br>
            <br>
            <br>
        </div>
    </div>
    <br>
    <br>

    <?php ActiveForm::end(); ?>



