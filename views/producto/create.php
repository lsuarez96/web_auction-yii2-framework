<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Producto */
/* @var $images app\models\ImageFiles */

$this->title = 'Datos del Producto';
$this->params['breadcrumbs'][] = ['label' => 'Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="producto-create">

    <h1><?= Html::encode($this->title) ?></h1>
<p>Por favor introduzca la siguiente informacion</p><br>
    <?= $this->render('_form', [
        'model' => $model,
        'images'=>$images,
    ]) ?>

</div>
