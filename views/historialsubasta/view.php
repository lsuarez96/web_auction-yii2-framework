<?php

use app\models\Subasta;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\HistorialSubasta */

$anuncio="";
if(($id_sub)>0) {
    $anuncio = \app\models\Producto::findOne(['id_producto' => Subasta::findOne(['id_subasta' => $id_sub])->id_producto])->anuncio;
    $this->params['breadcrumbs'][] = ['label' => 'Subasta', 'url' => 'index.php?r=producto%2Fview&id=' . Subasta::findOne(['id_subasta' => $id_sub])->id_producto];
}
$this->title = ($anuncio)!=""? $anuncio : "Producto no encontrado";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="historial-subasta-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    if (!empty($model)) {
        foreach ($model as $item) {
            echo '<div class="row">';
            echo '<h3 class="col-sm-12"><b>' . \app\models\Usuario::findOne(['id_usuario' => $item->id_usuario])->nom_user . ': </b> $' . $item->precio . '</h3></div>';

        }
    }else{
        echo '<div class="alert alert-info"><h3>Historial Vacio</h3><p>Este producto no tiene un historial de subastas actualmente!!!</p></div>';
    }
    ?>
</div>
