<?php

use app\assets\AppAsset;
use app\assets\ProductAsset;
use app\models\Subasta;
use app\models\Usuario;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Productos';
$this->params['breadcrumbs'][] = $this->title;
\app\assets\ProdAsset::register($this);
//ProductAsset::register($this);
?>
<div class="producto-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="container" id="filter-product-list" filter="<?= $filter ?>">
        <?php
        $resultSet = $dataProvider;
        if (count($resultSet) == 0) {
            echo '<div class="row">';
            echo '<div class="col-sm-3></div>';
            echo '<div class="col-sm-6">';
            echo '<div class="alert alert-info" style="text-align: center;">';
            echo '<h3>No existen productos de este tipo</h3>';
            echo '<p>Actualmente no se esta subastando ningun producto en esta categoria</p>';
            echo '<p>Vuelva dentro de un rato, seguro que tenemos lo que busca!!!</p>';
            echo '</div>';
            echo '</div>';
            echo '<div class="col-sm-3"></div>';
            echo '</div class="row">';
        } else {

            foreach ($resultSet as $item) {

                $subasta_relacionada = Subasta::findOne(['id_producto' => $item['id_producto']]);
                echo '<div index="' . $item['id_producto'] . ' filter="' . $filter . '" class="pagina" page="1">';
                echo '<button class="btn row col-sm-12" style="text-align:left; margin:5px;" data-toggle="collapse" data-target="#collapse' . $item['id_producto'] . '"><b>$' . Subasta::findOne(['id_producto' => $item['id_producto']])->precio_actual . '</b> ' . $item['anuncio'] . '</button>';
                echo '<div class="row collapse ' . $item['id_producto'] . '" style=" margin:15px; box-shadow: 1px 1px 1px 1px rgba(0,0,0,0.4); padding: 15px;" id=collapse' . $item['id_producto'] . '>';//contenedor del producto
                echo '<div class="row info-pujar">';//contenedor de la informacion y el formulario de pujar
                echo '<div class="col-sm-9 information">';//contenedor de la informacion
                echo '<h4>Subastado por:<a href="index.php?r=usuario/visit-profile&id_user=' . $item['id_usuario'] . '"><span>' . \app\models\Usuario::find()->where(['id_usuario' => $item['id_usuario']])->limit(1)->all()[0]->nom_user . '</span></a></h4>';
                echo '<h4>Anuncio: <span>' . $item['anuncio'] . '</span></h4>';
                echo '<h4 class="precio' . $item['id_producto'] . '" >Precio actual: <span>$' . Subasta::findOne(['id_producto' => $item['id_producto']])->precio_actual . '</span></h4>';
                echo '<h4>Fin de la subasta: ' . $item['fecha_limite'] . '</h4>';
                $lider = Usuario::findOne(['id_usuario' => $subasta_relacionada->id_usuario]);
                if ($lider != null) {
                    echo '<h4>Lider de la subasta: ' . $lider->nom_user . '</h4>';
                }
                echo '<h4 class="actividad' . $item['id_producto'] . '">Actividad del producto: ' . $subasta_relacionada->actividad . ' pujas</h4>';
                echo '<h4><span>Descripcion:</span></h4>';
                echo '<p>' . $item['descripcion'] . '</p>';
                echo '</div>';//fin informacion
                echo '<div class="col-sm-3 formulario">';
                echo '<div class="contador-tiempo" prod="' . $item['id_producto'] . '" style="margin-top: 150px;">';//contador

                echo '</div>';//fin contador
                echo '</div>';//fin formulario
                echo '</div>';//fin formulario e informacion
                echo Html::a('Mas Detalles>>', 'index.php?r=producto/view&id=' . $item['id_producto'], ['method=>get']);
                echo '</div>';
                echo '</div>';
            }
        }
        ?>
    </div>
</div>


