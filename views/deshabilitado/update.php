<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Deshabilitado */

$this->title = 'Update Deshabilitado: ' . $model->id_deshabilitado;
$this->params['breadcrumbs'][] = ['label' => 'Deshabilitados', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_deshabilitado, 'url' => ['view', 'id' => $model->id_deshabilitado]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="deshabilitado-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
