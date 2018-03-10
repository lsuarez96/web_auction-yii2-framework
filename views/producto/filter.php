<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchProducto */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Productos';
$this->params['breadcrumbs'][] = $this->title;
\app\assets\AppAsset::register($this);
?>
<div class="producto-index">

    <h1><?= Html::encode($this->title) ?></h1>
   

    <?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id_usuario',
                'value' => 'idUsuario.nom_user'],
            'anuncio',
            'fecha_limite',
            [
                'attribute' => 'precio',
                'value' => 'subasta.precio_actual',
            ],
            [
                'attribute' => 'subasta.actividad',
                'value' => 'subasta.actividad',

            ],
            //'foto'=>'<span class="glyphicon-apple"></span>',
            [
                'template'=>'{view}',
                'class'=>'yii\grid\ActionColumn',
                'buttons'=>[
                    'view'=>function($url, $model, $key) {
                        $delete='';
                        if(\app\models\Usuario::roleInArray(['Administrador'])){
                            $delete=Html::a ( '<span class="glyphicon glyphicon-trash"></span> ', ['producto/delete', 'id' =>Yii::$app->getSecurity()->encryptByKey($model->id_producto,Yii::$app->params['salt']),'data'=>['method'=>'post']] );
                        }else{
                            $delete='';
                        }
                        return Html::a ( '<span class="glyphicon glyphicon-eye-open"></span> ', ['producto/view', 'id' => $model->id_producto] ).$delete;
                    },

                ]

            ]
        ],
    ]);
    ?>
    <?php Pjax::end(); ?></div>
<?php
//$script = <<<JS
// $("span.glyphicon.glyphicon-trash").remove();
//    $("span.glyphicon.glyphicon-pencil").remove();
//JS;
//if (\app\models\Usuario::roleInArray(['Usuario'])) {
//   // $this->registerJs($script);
//}
?>
