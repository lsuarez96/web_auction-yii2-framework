<?php
/**
 * Created by PhpStorm.
 * User: Luisito Suarez
 * Date: 4/12/2017
 * Time: 11:41
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Pais;

/* @var $this yii\web\View */
/* @var $model app\models\Usuario */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ModifyUserModel-form registration-left" style="align-content: center; align-items: center;">


    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-10\">{input}</div>\n<div class=\"col-lg-offset-2 col-lg-2\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label', 'style' => 'text-align: right; '],
        ],
    ]); ?>

    <?= $form->field($model, 'nom_user')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'apellido')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'correo')->textInput(['maxlength' => true,'type'=>'email']) ?>
    <?= $form->field($model, 'pais')->dropDownList(\yii\helpers\ArrayHelper::map(Pais::find()->select(['id_pais','nombre_pais'])->all(),'id_pais','nombre_pais'),['class'=>'form-control inline-block']) ?>
    <?= $form->field($model, 'claveOld')->passwordInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'clave')->passwordInput(['maxlength' => true]) ?>
    <?=$form->field($model,'claverepeat')->passwordInput(['maxlength'=>true])?>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <?= Html::submitButton('Modificar Informacion', ['class' => 'btn-submit']) ?>
        </div>
    </div>
    <br>
    <br>

    <?php ActiveForm::end(); ?>

</div>
