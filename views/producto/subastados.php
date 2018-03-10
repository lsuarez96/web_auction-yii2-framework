<?php
/**
 * Created by PhpStorm.
 * User: Luisito Suarez
 * Date: 25/11/2017
 * Time: 09:58
 */
\app\assets\AppAsset::register($this);
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Productos Subastados';
$this->params['breadcrumbs'][] = $this->title;
?>

    <div class="col-md-10 center-block">
        <div id="prod-list">

        </div>

    </div>

<?php
$script = <<<JS
if($("#prod-list div").length==0){
$.get('index.php?r=producto/subastados',{page:1}, function (data) {
                    var amountData = $("#prod-list div.pagina").length;
                    if (data.length > 0) {
                        $("#prod-list").append(data);
                    } else if ($('.load-info').attr('id') == undefined) {
                        $("#prod-list").append('<div class="load-info container-fluid" id="no-more-data" style="height: 40px; width: 100%; text-align: center; margin-top: 40px;  display: inline-block;"><div class=" alert alert-info"><p >No hay mas informacion para ser cargada</p></div></div>');
                    }
                });
}
  $(window).scroll(function () {
        if (Math.round($(window).scrollTop()) == Math.round($(document).height() - $(window).height())) {
           
            var page = parseInt($(".pagina:last").attr('page'));
            
                if (page == undefined) {
                    page = 0;
                }
                page = parseInt(page) + 1;
                $.get('index.php?r=producto/subastados', {page: page}, function (data) {
                    var amountData = $("#prod-list div.pagina").length;
                    if (data.length > 0) {
                        $("#prod-list").append(data);
                    } else if ($('.load-info').attr('id') == undefined) {
                        $("#prod-list").append('<div class="load-info container-fluid" id="no-more-data" style="height: 40px; width: 100%; text-align: center; margin-top: 40px;  display: inline-block;"><div class=" alert alert-info"><p >No hay mas informacion para ser cargada</p></div></div>');
                    }
                });
                }});
JS;
$this->registerJs($script);
?>