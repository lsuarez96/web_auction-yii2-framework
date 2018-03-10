<?php


use app\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\models\Usuario */

$this->title = 'Redireccionando...';
$this->params['breadcrumbs'][] = ['label' => 'Login', 'url' => ['..\site\login']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>Confirme su registro en el enlace enviado a su correo. Gracias por escoger ExpressDealer</p>
    


</div>
