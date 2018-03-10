<?php
/**
 * Created by PhpStorm.
 * User: Luisito Suarez
 * Date: 4/11/2017
 * Time: 16:00
 */

use app\assets\AppAsset;
use app\models\Subasta;
use app\models\Usuario;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

//AppAsset::register($this);
\app\assets\AppAsset::register($this);
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$tipo = \app\models\Tipo::find()->where(['id_tipo' => $idTipo])->limit(1)->all()[0]->nom_tipo;
$this->title = 'Productos de tipo ' . $tipo;
$this->params['breadcrumbs'][] = $this->title;
?>

<script type="text/javascript" id="sourcecode">
    $(function () {
        $('.scroll-pane').jScrollPane();
    });
</script>
<div class="col-md-9-mod  w_content ">
        <div class="women">
            <a><h4><?= $tipo ?></h4></a>
            <div class="clearfix"></div>
        </div>
    <div  id="type-product-list">
<!--<style scoped>-->
<!--    .grids_of_4 a:hover{-->
<!--        background-color: rgb(192,192,192)!important;-->
<!--    }-->
<!--</style>-->

        <?php
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


            $count = 0;
            foreach ($resultSet as $item) {
                if ($count % 4 == 0) {
                    echo '<div class="grids_of_4">';
                }
                $foto_url = \app\models\Foto::findFirstProductFoto($item->id_producto)->url;
                $subasta_relacionada = Subasta::findOne(['id_producto' => $item['id_producto']]);
                echo '<div class="grid1_of_4 pagina producto" typ="' . $item->tipo . '" page="1">';
                echo '<div class="content_box">';
                echo '<a href="index.php?r=producto/view&id=' . $item['id_producto'] . '">';
                echo '<div class="view view-fifth">';
                echo ' <img src="' . $foto_url . '" class="img-responsive" alt="" height="240" style="height:240px; width:100%;"/>';
                //echo '<div class="mask"><span class="info">'.$subasta_relacionada->precio_actual.'</span></div>';
                echo '</div>'; //fin view div
                echo '</a>';
                echo '</div>';//fin content box
                echo '<a href="index.php?r=producto/view&id=' . $item['id_producto'] . '"><div class="product-timer btn-xs" fecha="' . DateTime::createFromFormat('Y-m-d H:i:s', $item->fecha_limite)->format('Y/m/d H:i:s') . '" ></div></a>';
                echo '<p><span style="text-align: center;">' . $item['anuncio'] . '</span></p>';
                echo '</div>';//fin del grid de un producto
                $count++;
                if ($count == 4 || $count == 8 || $count == 12) {
                    echo '</div>';//cierre de la grilla de cuatro
                }

            }

        }
        ?>
    </div>

</div>
<?php
//                    $subasta_relacionada = Subasta::findOne(['id_producto' => $item['id_producto']]);
//                    echo '<div index="' . $item['id_producto'] . '" typ="' . $item['tipo'] . '" class="pagina producto" page="1">';
//                    echo '<button class="row col-sm-12" style="text-align:left; margin:5px;" data-toggle="collapse" data-target="#collapse' . $item['id_producto'] . '"><b>$' . Subasta::findOne(['id_producto' => $item['id_producto']])->precio_actual . '</b> ' . $item['anuncio'] . '</button>';
//                    echo '<div class="row collapse ' . $item['id_producto'] . '" style=" margin:15px; box-shadow: 1px 1px 1px 1px rgba(0,0,0,0.4); padding: 15px;" id=collapse' . $item['id_producto'] . '>';//contenedor del producto
//                    echo '<div class="row info-pujar">';//contenedor de la informacion y el formulario de pujar
//                    echo '<div class="col-sm-9 information">';//contenedor de la informacion
//                    echo '<h4>Subastado por:<a href="index.php?r=usuario/visit-profile&id_user=' . $item['id_usuario'] . '"><span>' . \app\models\Usuario::find()->where(['id_usuario' => $item['id_usuario']])->limit(1)->all()[0]->nom_user . '</span></a></h4>';
//                    echo '<h4>Anuncio: <span>' . $item['anuncio'] . '</span></h4>';
//                    echo '<h4 class="precio' . $item['id_producto'] . '" >Precio actual: <span>$' . Subasta::findOne(['id_producto' => $item['id_producto']])->precio_actual . '</span></h4>';
//                    echo '<h4>Fin de la subasta: ' . DateTime::createFromFormat('Y-m-d H:i:s',$item->fecha_limite)->format('Y/m/d H:i:s') . '</h4>';
//                    echo '<h4>Lider de la subasta: ' . Usuario::findOne(['id_usuario' => $subasta_relacionada->id_usuario])->nom_user . '</h4>';
//                    echo '<h4 class="actividad' . $item['id_producto'] . '">Actividad del producto: ' . $subasta_relacionada->actividad . ' pujas</h4>';
//
//                    echo '</div>';//fin informacion
//                    echo '<div class="col-sm-3 formulario">';
//                    echo '<div style="margin-top: 20px;" class="product-timer" fecha="'.DateTime::createFromFormat('Y-m-d H:i:s',$item->fecha_limite)->format('Y/m/d H:i:s').'" ></div>';
//
//                    echo '<form  prod="' . $item['id_producto'] . '">';
//                    echo '<div style="margin-top: 30px;" class="form-group">';
//                    $minimo = Subasta::findOne(['id_producto' => $item['id_producto']])->precio_actual + 1;
////                    if (!Yii::$app->user->isGuest) {
////                        echo '<div class="col-sm-7">';
////                        echo '<input type="number" class="form-control"  name="precio-puja"  min="' . $minimo . '" id="puja' . $item['id_producto'] . '" placeholder="$' . $minimo . '" style="margin: 5px;">';
////                        echo '</div>';
////                        //echo '<div class="col-sm-4>';
////                        echo '<input type="button" value="Pujar" class="btn btn-danger" prod="' . $item['id_producto'] . '" style="margin: 5px;">';
////                    }  // echo '</div>';
//                    echo '</div>';//fin form group
//                    echo '</form>';//fin form
//                    echo '</div>';//fin formulario
//
//                    echo '</div>';//fin formulario e informacion
//                    echo Html::a('Mas Detalles>>', 'index.php?r=producto/view&id=' . $item['id_producto'], ['method=>get']);
//                    echo '</div>';
//                    echo '</div>';
?>