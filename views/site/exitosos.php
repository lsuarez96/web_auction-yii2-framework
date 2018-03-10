<?php

use app\models\Pais;
use app\models\Usuario;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
\app\assets\AppAsset::register($this);
$this->title = 'Usuarios eexitosos';
$this->params['breadcrumbs'][] = $this->title;



?>

<div class="page-container" id="pdfContainer">
    <?php
    $exitosos = Usuario::getExitosos();
    $cont = '<div class="col-md-3"><img src="assets/images/logo.png" with="100%" style="with=100%; height: 70px;"></div><div class="col-md-12" >';


    if (($exitosos!=null)) {


        $table = '<table class="table-responsive table-hover" style="margin: 0 auto;"><thead><tr><th>Usuario</th><th>Correo</th><th>Pais</th><th>Reputacion</th></tr></thead><tbody>';
        foreach ($exitosos as $exitoso) {
                if($exitoso->id_usuario!=null) {
                    $table .= '<tr><td>' . $exitoso->nom_user . '</td><td>' . $exitoso->correo . '</td><td>' . Pais::findOne(['id_pais' => $exitoso->pais])->nombre_pais . '</td><td>' . Usuario::calcularReputacion($exitoso->id_usuario) . '</td></tr>';
                }
        }
        $table .= '</tbody></table></div>';
        $cont .= $table;
        echo $cont;
    }
    ?>
</div>


<?php
$script = <<<JS
$(document).ready(function(){
    $.get('index.php?r=site/pdf',function(data){
        if(data.length>0){
            var doc = new jsPDF('p','pt','letter');
            //alert(data);
          $("#pdfContainer").html(data);
// We'll make our own renderer to skip this editor
            var specialElementHandlers = {
                '#bypassme': function(element, renderer){
                    return true;
                }
            };
            //$("#pdfContainer").html(data);

// All units are in the set measurement for the document
// This can be changed to "pt" (points), "mm" (Default), "cm", "in"
          doc.fromHTML(
data, // HTML string or DOM elem ref.
30, // x coord
12, // y coord
{
'width': 6, // max width of content on PDF
'elementHandlers': specialElementHandlers
});

$("#export-pdf").attr('href',doc.output('dataurl'));

}
    });
});
JS;
//$this->registerJs($script);

?>
