<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Historial Subastas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="historial-subasta-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Historial Subasta', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_historial_subasta',
            'id_subasta',
            'id_usuario',
            'precio',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
