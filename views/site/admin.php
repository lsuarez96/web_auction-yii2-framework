<?php
/**
 * Created by PhpStorm.
 * User: Luisito Suarez
 * Date: 18/11/2017
 * Time: 00:13
 */
use app\assets\AppAsset;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $dataProvider yii\data\ActiveDataProvider */

AppAsset::register($this);
$this->title = 'Administrar sitio';
$this->params['breadcrumbs'][] = $this->title;

$exitosos=\app\models\Usuario::getExitosos();
$deshabilitados=\app\models\Usuario::getUsuariosDeshabilitados();
?>


<div class="container-fluid" id="admin-page">
    <div class="col-sm-4">
        <h2><a href="index.php?r=usuario/index" class="btn btn-block btn-success">Gestionar Usuarios</a></h2>
        <p>
            Gestione los usuarios del sitio. Como administrador usted puede habilitar, dehabilitar y banear usuarios que no
            cumplan con las regulaciones del sitio...
        </p>
    </div>
    <div class="col-sm-4">
        <h2><a href="index.php?r=site/pdf" class="btn btn-block btn-success">Usuarios exitosos</a></h2>
        <p>
            Usuarios con mayor exitos de venta. Genere un reporte pdf con los usuarios que mayor actividad
            de venta han tenido hasta el momento...
        </p>
    </div>

    <div class="col-sm-4">

            <h2><a href="index.php?r=producto/scrollall&page=-1" class="btn btn-block btn-success">Gestionar subastas</a></h2>
            <p>
               Elimine las subastas que no cumplan con las regulaciones del sitio...
            </p>

    </div>
</div>


<?php
$script = <<<JS
$("span.glyphicon.glyphicon-trash").remove();
$("span.glyphicon.glyphicon-pencil").remove();
JS;
?>
