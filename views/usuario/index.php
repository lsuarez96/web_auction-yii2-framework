<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $data \app\models\Usuario[] */

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;
\app\assets\ProdAsset::register($this);
?>
<div class="usuario-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <button data-target="#all" data-toggle="collapse" class="btn btn-block btn-success">Todos</button>
            </div>
            <div class="col-md-4">
                <button data-target="#dehab" data-toggle="collapse" class=" btn btn-block btn-danger">Usuarios
                    Deshabilitados
                </button>
            </div>
        </div>
    </div>
    <div class="collapse col-md-10" id="all">
        <table class="table table-hover">
            <tbody>
            <?php
            foreach ($data as $item) {
                $disabled=$item->getDeshabilitados()->one()!=null;
                if(!$disabled) {
                    echo '<tr style="background-color: #9cffa8"><td><h2><a href="index.php?r=usuario/visit-profile&id_user=' . $item->id_usuario . '">' . $item->nom_user . '</a></h2></td><td>' . $item->correo . '</td></tr>';
                }
            }
            ?>
            </tbody>
        </table>
    </div>
    <div class="collapse col-md-10" id="dehab">
        <table class="table table-hover">
            <tbody>
            <?php
            $deshab = \app\models\Usuario::getUsuariosDeshabilitados();
            foreach ($deshab as $item) {
                echo '<tr style="background-color: #ff9c9c"><td><h2><a href="index.php?r=usuario/visit-profile&id_user=' . $item->id_usuario . '">' . $item->nom_user . '</a></h2></td><td>' . $item->correo . '</td></tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
