<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\HistorialSubasta */

$this->title = 'Create Historial Subasta';
$this->params['breadcrumbs'][] = ['label' => 'Historial Subastas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="historial-subasta-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
