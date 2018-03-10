<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RolUser */

$this->title = 'Update Rol User: ' . $model->id_rol_user;
$this->params['breadcrumbs'][] = ['label' => 'Rol Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_rol_user, 'url' => ['view', 'id' => $model->id_rol_user]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rol-user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
