<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Deshabilitado */

$this->title = $model->id_deshabilitado;
$this->params['breadcrumbs'][] = ['label' => 'Deshabilitados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deshabilitado-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_deshabilitado], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_deshabilitado], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_deshabilitado',
            'usuario',
            'tiempo',
            'razon',
        ],
    ]) ?>

</div>
