<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Notificaciones';
$this->params['breadcrumbs'][] = $this->title;
\app\assets\AppAsset::register($this);
?>
<div class="notificaciones-index">

    <h1><?= Html::encode($this->title) ?></h1>
<div class="row" style="margin: 20px;">

    <div class="col-md-12 center-block" id="notifications-container" sec="notificaciones">
    <?php
    if ($resultset == false){
        echo '<div class="alert alert-info load-info" id="no-more-data" style="text-align: center;"><h3>No tiene notificaciones</h3><p>Parece que no tienes ninguna notificacion, comienza a seguir una subasta y te informaremos cuando ocurra algun cambio</p></div>';
    }else{
        echo '<table class="table table-responsive table-hover">';
        echo '<tbody id="notif-table">';
        foreach ($resultset as $item) {
            if($item->nuevo) {
                echo '<tr class="alert-info pagina" pag="1"><td><span class="badge">new</span>&nbsp;' . $item->nota . '</td></tr>';
            }else{
                echo '<tr class="pagina" pag="1"><td>' . $item->nota . '</td></tr>';
            }
            \app\models\Notificaciones::updateAll(['nuevo'=>false],['id_notificaciones'=>$item->id_notificaciones]);

        }
        echo '</tbody>';
        echo '</table>';

    }
    ?>
    </div>
<div class="clearfix"></div>
</div>
<div/>

<?php
$script=<<<JS

 $(window).scroll(function () {
        if (Math.round($(window).scrollTop()) == Math.round($(document).height() - $(window).height())) {
          alert($("tr.pagina").last().attr('pag'));
                page = parseInt($("#notificaciones-container .pagina:last").attr('pag'));

                if (page == undefined) {
                    page=0;
                }
                page = parseInt(page) + 1;
                alert(page);
                $.get('index.php?r=notificaciones/notificaciones-scroll', {page: page}, function (data) {

                    if (data.length > 0) {
                        $("#notif-table").append(data);
                    } else if ($('.load-info').attr('id') == undefined) {
                        $("#notifications-container").append('<div class="load-info container-fluid" id="no-more-data" style="height: 40px; width: 100%; text-align: center; margin-top: 40px;  display: inline-block;"><div class=" alert alert-info"><p >No hay mas informacion para ser cargada</p></div></div>');
                    }
                });

        }
    });


JS;
//$this->registerJs($script);
?>