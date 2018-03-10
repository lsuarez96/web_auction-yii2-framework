<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Deshabilitado */

$this->title = 'Create Deshabilitado';
$this->params['breadcrumbs'][] = ['label' => 'Deshabilitados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deshabilitado-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
