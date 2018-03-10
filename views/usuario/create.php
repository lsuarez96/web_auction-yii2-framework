<?php


use app\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\models\Usuario */

$this->title = 'Crear Cuenta';
$this->params['breadcrumbs'][] = ['label' => 'Login', 'url' => ['/site/login']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
