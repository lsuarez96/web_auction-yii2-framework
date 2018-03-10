<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Subasta */

$this->title = 'Update Subasta: ' . $model->id_subasta;
$this->params['breadcrumbs'][] = ['label' => 'Subastas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_subasta, 'url' => ['view', 'id' => $model->id_subasta]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="subasta-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
