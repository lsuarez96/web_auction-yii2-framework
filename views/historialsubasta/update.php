<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\HistorialSubasta */

$this->title = 'Update Historial Subasta: ' . $model->id_historial_subasta;
$this->params['breadcrumbs'][] = ['label' => 'Historial Subastas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_historial_subasta, 'url' => ['view', 'id' => $model->id_historial_subasta]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="historial-subasta-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
