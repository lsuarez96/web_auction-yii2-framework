<?php
/**
 * Created by PhpStorm.
 * User: Luisito Suarez
 * Date: 13/11/2017
 * Time: 18:09
 */
$this->title = 'Productos';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="main-container">
    <div class="row" id="filter-form">
        <?php
        $f = \yii\widgets\ActiveForm::begin([
            'method' => 'get',
            'action' => \yii\helpers\Url::toRoute('producto/all'),
            'enableClientValidation' => false,
        ]);
        ?>
        <div class="container-fluid" style="display: inline;">
            <div class=" form-group inline-group col-sm-3 input-group-sm">
                <label class="col-sm-4" style="text-align: right;"><b>Precios:</b></label>
                <?= $f->field($filterProduct, 'minPrice')->textInput( ['type'=>'number','class' => 'col-sm-4 input-sm', 'style'=>'margin-top:0px;','min' => '0', 'placeholder' => '$min']) ?>
                <?= $f->field($filterProduct, 'maxPrice')->textInput( ['type'=>'number','class' => 'col-sm-4 input-sm', 'style'=>'margin-top:0px;','placeholder' => '$max']) ?>
            </div>
            <div class="form-group col-sm-4 input-group-sm">
                <label class="col-sm-3" style="text-align: right;"><b>Fecha:</b></label>
                <?= $f->field($filterProduct, 'date')->textInput( ['type'=>'date','class' => 'col-sm-4 input-sm', 'placeholder' => 'fecha']) ?>

            </div>
            <div class=" form-group inline-group col-sm-4" style="display: inline-block;">
                <div class="col-sm-4">
                    <label style="text-align: right;"><b>Actividad:</b></label>
                </div>
                <?= $f->field($filterProduct, 'actividad')->dropDownList(['','mayor', 'menor'], ['class' => 'col-sm-8 input-sm']) ?>
            </div>

        </div>
        <?= \yii\helpers\Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
        <?php $f->end() ?>
    </div><!--End filter form-->

    <div class="container">
        <?php
        var_dump($date);
        var_dump($query);
        if($resultset!=false) {
            var_dump($date);

            foreach ($resultset as $item) {
                var_dump($item);
            }
        }
        ?>
    </div>

</div>
