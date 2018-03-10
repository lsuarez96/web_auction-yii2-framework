<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Subasta */

$this->title = 'Create Subasta';
$this->params['breadcrumbs'][] = ['label' => 'Subastas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subasta-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
