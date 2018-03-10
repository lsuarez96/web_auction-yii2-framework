<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Subasta */

$this->title = $model->id_subasta;
$this->params['breadcrumbs'][] = ['label' => 'Subastas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subasta-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_subasta], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_subasta], [
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
            'id_subasta',
            'id_producto',
            'id_usuario',
            'precio_actual',
            'actividad',
            'terminada:boolean',
        ],
    ]) ?>

</div>
