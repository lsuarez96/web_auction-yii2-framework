<?php


use app\assets\AppAsset;
use app\models\ImageFiles;
use yii\helpers\Html;

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Producto */
/* @var $images app\models\ImageFiles */

/* @var $form yii\widgets\ActiveForm */
$model->id_usuario = Yii::$app->user->id;
AppAsset::register($this);
?>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="producto-form col-md-6" style="align-content: center; align-items: center;">

            <?php $form = ActiveForm::begin(['method' => 'post', 'options' => ['enctype' => 'multipart/form-data', 'clientValidation' => true]]); ?>
            <?= $form->field($model, 'anuncio')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'precio')->textInput(['type' => 'number', 'step' => '0.05']) ?>

            <?= $form->field($model, 'tipo')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\Tipo::find()->select(['id_tipo', 'nom_tipo'])->all(), 'id_tipo', 'nom_tipo'), ['id' => 'tipos']) ?>

            <?= $form->field($model, 'sub_tipo')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\Subtipo::find()->all(), 'id_sub_tipo', 'sub_tipo')) ?>
            <?= $form->field($model, 'descripcion')->textarea(['maxlength' => true]) ?>
            <?php echo $form->field($model, 'fecha_limite')->textInput(['type'=>'datetime-local','class'=>'date']); ?>

            <?php
            //$images=new ImageFiles();
            echo $form->field($images, 'file[]')->fileInput(['multiple' => true]);
            ?>
            <div class="form-group col-lg-offset-2 col-lg-8">
                <?= Html::submitButton($model->isNewRecord ? 'Subastar' : 'Modificar', ['class' => $model->isNewRecord ? 'btn-submit btn-lg btn-block' : 'btn-submit btn-lg btn-block']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
    <div class="col-md-3"></div>
<?php

$script = <<<JS
//here is all my javascript bullshit
//setting the change method of the types dropdown with id='producto-tipo'
$(document).ready(function() {
  var type_id=$("#tipos").val();    
      $.get('index.php?r=subtipo/get-subtypes',{type_id:type_id},function(data) {
      $("#producto-sub_tipo").html(data);
              
      });
});
    $("#tipos").change(function() {
    var type_id=$(this).val();    
      $.get('index.php?r=subtipo/get-subtypes',{type_id:type_id},function(data) {
      $("#producto-sub_tipo").html(data);
              
      });
        
    });
JS;
$this->registerJs($script);
?>