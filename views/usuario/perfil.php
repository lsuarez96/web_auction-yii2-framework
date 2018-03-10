<?php
/**
 * Created by PhpStorm.
 * User: Luisito Suarez
 * Date: 4/11/2017
 * Time: 03:38
 */

use app\assets\AppAsset;
use app\models\Subasta;
use yii\helpers\Html;

//AppAsset::register($this);
\app\assets\ProdAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\models\Usuario */
if (!isset($id_user) && !Yii::$app->user->isGuest) {
    $user = \app\models\Usuario::findOne(['id_usuario' => Yii::$app->user->id]);
} else if (isset($id_user)) {
    $user = \app\models\Usuario::findOne(['id_usuario' => $id_user]);
}
$this->title = "Perfil";
$this->params['breadcrumbs'][] = ['label' => 'Perfil', 'url' => ['..\usuario\perfil']];

?>
<div class="page-container">


    <div class="container-fluid">
        <div class="container-fluid" style="margin:20px;">
            <div class="col-md-6">
                <h3>Usuario: <span><?= ($user->nom_user) ?></span></h3>
                <h3>Nombre: <span><?= $user->nombre ?></span></h3>
                <h3>Apellido(s): <span><?= $user->apellido ?></span></h3>
                <h3>Email: <a target="_blank" href="<?= $user->correo ?>" rel="search"><?= $user->correo ?></a></h3>
                <h3>Pais:
                    <span><?= \app\models\Pais::find()->where(['id_pais' => $user->pais])->limit(1)->all()[0]->nombre_pais ?></span>
                </h3>

                <?php
                if (!isset($id_user)) {

                    if (app\models\Usuario::roleInArray(['Usuario'])) {
                        echo '<h3>Reputacion: ' . \app\models\Usuario::calcularReputacion(Yii::$app->user->id) . '</h3>';

                        echo '<a class="btn btn-danger" style="color: white;" href="index.php?r=usuario%2Fupdate&id=' . $user->id_usuario . '">Modificar</a>';

                    }
                } else {
                    echo '<h3>Reputacion: ' . \app\models\Usuario::calcularReputacion($id_user) . '</h3>';
                    $us = \app\models\Usuario::findOne(['id_usuario' => $id_user]);
                    if (\app\models\Usuario::roleInArray(['Administrador']) && $id_user != Yii::$app->user->id) {
                        echo '<div class="col-md-12">';
                        echo '<div class="col-md-6">';
                        if ($us->getDeshabilitados()->one() != null) {
                            echo '<a class="btn btn-success btn-block" href="index.php?r=usuario/habilitar&id=' . $id_user . '">Habilitar</a>';

                        } else {
                            echo '<a class="btn btn-warning btn-block" href="index.php?r=usuario/deshabilitar&id=' . $id_user . '">Deshabilitar</a>';
                        }
                        echo '</div>';
                        echo '<div class="col-md-6"><a class="btn btn-danger btn-block" href="index.php?r=usuario/eliminar&id=' . $id_user . '">Eliminar</a></div>';
                        echo '</div>';
                    }
                }
                ?>
            </div>


        </div>
        <div class="col-md-11">
            <?php
            if (!isset($id_user) && \app\models\Usuario::roleInArray(['Usuario']) || (isset($id_user) ? ($id_user == Yii::$app->user->id) : false)) {
                if (Yii::$app->user->isGuest || \app\models\Usuario::roleInArray(['Usuario'])) {
                    echo '<div class="row">';
                    echo '<button class="col-sm-12 btn btn-default" data-toggle="collapse" data-target="#subastasSeguidas">Subastas Seguidas</button>';
                    echo '</div>';
                    echo '<div class="collapse row" id="subastasSeguidas">';
                    $products = \app\models\Producto::findProductsFollowedByUser($user->id_usuario);
                    foreach ($products as $product) {
                        echo '<div class="row" style="box-shadow: 1px 1px 1px rgba(0,0,0,0.5) ;">';
                        echo '<div class="col-sm-8">';
                        echo '<h3>' . $product->anuncio . '</h3>';
                        echo '<h3>Clasificacion:<h4>' . \app\models\Tipo::find()->where(['id_tipo' => $product->tipo])->limit(1)->all()[0]->nom_tipo . '/' . \app\models\Subtipo::find()->where(['id_sub_tipo' => $product->sub_tipo])->limit(1)->all()[0]->sub_tipo . '</h4></h3>';
                        echo '<h3>Precio: <h4>$' . \app\models\Subasta::find()->where(['id_producto' => $product->id_producto])->limit(1)->all()[0]->precio_actual . '</h4></h3>';
                        echo '<h3>Actividad: <h4>' . Subasta::findOne(['id_producto' => $product->id_producto])->actividad . '</h4></h3>';
                        echo '<h3>Descripción:</h3>';
                        echo '<p>' . $product->descripcion . '</p>';
                        echo '<a href="index.php?r=producto/view&id=' . $product->id_producto . '">Mas Detalles>></a>';
                        echo '</div>';
                        echo '<div class="col-sm-4">';
                        echo '<image src="' . \app\models\Foto::findFirstProductFoto($product->id_producto)->url . '" width="100%" height="300px" style="margin:10px;">';
                        echo '</div></div>';
                    }


                    echo '</div>';
                }
            }
            ?>
            <?php
            if (\app\models\Usuario::roleInArray(['Usuario']) || isset($id_user) && $id_user != Yii::$app->user->id) {

                echo '<div class="row">';
                echo '<button class="col-sm-12 btn btn-default" data-toggle="collapse" data-target="#misSubastas">Articulos Subastados</button>';
                echo '</div>';

                echo '<div class="collapse row" id="misSubastas">';

                $products = \app\models\Producto::findUserProducts($user->id_usuario);
                foreach ($products as $product) {
                    $subasta = \app\models\Subasta::findOne(['id_producto' => $product->id_producto]);
                    echo '<div class="row producto" style="box-shadow: 1px 1px 1px rgba(0,0,0,0.5) ;">';
                    echo '<div class="col-sm-8">';
                    echo '<h3>' . $product->anuncio . '</h3>';
                    echo '<h3>Clasificacion:<h4>' . \app\models\Tipo::find()->where(['id_tipo' => $product->tipo])->limit(1)->all()[0]->nom_tipo . '/' . \app\models\Subtipo::find()->where(['id_sub_tipo' => $product->sub_tipo])->limit(1)->all()[0]->sub_tipo . '</h4></h3>';
                    echo '<h3>Precio: <h4>$' . \app\models\Subasta::find()->where(['id_producto' => $product->id_producto])->limit(1)->all()[0]->precio_actual . '</h4></h3>';
                    echo '<div class="row">';
                    echo '<div class="col-sm-6">';
                    echo '<h3>Fecha Limite: <h4>' . DateTime::createFromFormat('Y-m-d H:i:s', $product->fecha_limite)->format('Y/m/d H:i:s') . '</h4></h3>';
                    $fechaActual = new DateTime('now');
                    echo '<h3>Fecha Actual: <h4>' . $fechaActual->format('Y/m/d H:i:s') . '</h4></h3>';
                    echo '</div>';
                    echo '<div class="col-sm-6 btn btn-lg">';
                    echo '<div style="margin-top: 20px;" class="product-timer" fecha="' . DateTime::createFromFormat('Y-m-d H:i:s', $product->fecha_limite)->format('Y/m/d H:i:s') . '" ></div>';
                    echo '</div>';
                    echo '</div>';
                    echo '<a style="margin-top:30px;" href="index.php?r=producto/view&id=' . $product->id_producto . '">Mas Detalles>></a>';
                    echo '</div>';
                    echo '<div class="col-sm-4">';
                    $fechaSubasta = DateTime::createFromFormat('Y-m-d H:i:s', $product->fecha_limite);
                    $fechaActual = new DateTime('now');
                    if ($fechaSubasta <= $fechaActual && $product->id_usuario == Yii::$app->user->id && \app\models\Subasta::findOne(['id_producto' => $product->id_producto])->terminada == false) {
                        if ($subasta->id_usuario != null && $subasta->actividad > 0) {
                            echo '<button class="btn btn-success vendido" style=" margin-top:20px; margin-bottom: 15px; margin-left: 45%;" prod="' . $product->id_producto . '">Vendido</button>';
                        } else {
                            echo '<a href="index.php?r=producto/create" class="btn btn-danger" style=" margin-top:20px; margin-bottom: 15px; margin-left: 30%;" >No vendido, subastar otro producto</a>';
                        }
                    }
                    echo '<image src="' . \app\models\Foto::findFirstProductFoto($product->id_producto)->url . '" width="100%" height="300px" style="margin:10px;">';
                    echo '</div>';
                    echo '</div>';

                }
                echo '</div>';
            }
            ?>
            <?php
            //            if (\app\models\Usuario::roleInArray(['Administrador'])) {
            //                echo '<div class="row">';
            //                echo '<button class="col-sm-12 btn btn-default" data-toggle="collapse" data-target="#deshabilitados">Usuarios Deshabilitados</button>';
            //                echo '</div>';
            //
            //                echo '<div class="collapse row" id="deshabilitados">';
            //                $deshab = \app\models\Deshabilitado::find()->all();
            //                foreach ($deshab as $item) {
            //                    $rel_user = \app\models\Usuario::findOne(['id_usuario' => $item->usuario]);
            //                    echo '<div class="row" id="usuario'.$item->usuario.'" style="box-shadow: 1px 1px 1px rgba(0,0,0,0.5) ;">';
            //                    echo '<div class="col-sm-6">';
            //                    echo '<h3>Nombre de usuario: <span>' . $rel_user->nom_user . '</span></h3>';
            //                    echo '<h3>Nombre: <span>' . $rel_user->nombre . '</span></h3>';
            //                    echo '<h3>Apellidos: <span>' . $rel_user->apellido . '</span></h3>';
            //                    echo '<h3>Razon: <span>' . $item->razon . '</span></h3>';
            //                    echo '<a href="index.php?r=usuario/visit-profile&id_user=' . $rel_user->id_usuario . '">Mas Detalles>></a>';
            //                    echo '</div>';
            //                    echo '<div class="col-sm-6">';
            //                    echo '<div style="margin-top: 20px;">';
            //                    echo '<button class="btn-danger btn-lg habilitar" tipo="habilitar" user="' . $rel_user->id_usuario . '" >Habilitar</button>';
            //                    echo '<button class="btn-danger btn-lg eliminar" tipo="Eliminar" user="' . $rel_user->id_usuario . '">Eliminar</button>';
            //                    echo '</div>';
            //                    echo '<div class="producto"><div class="product-timer btn-lg" fecha="' . DateTime::createFromFormat('Y-m-d H:i:s', $item->tiempo)->format('Y/m/d H:i:s') . '" ></div></div>';
            //                    echo '</div>';
            //                    echo '</div>';
            //                }
            //                echo '</div>';
            //            }
            ?>
        </div>
    </div>


</div>

</div>
<?php
$script = <<<JS
$(".vendido").click(function(event) {
  var id_prod=$(this).attr("prod");
  if(id_prod!=undefined){
  $.get('index.php?r=subasta/register-sell',{id_prod:id_prod},function(data) {
    var res=$.parseJSON(data);
    if(res.successfull==true){
    $(this).remove();
    }else{
    alert("Ha ocurrido un error registrando la venta de su producto...");
    }
  }).fail(function() {
    console.log("fallo regist venta");
  });
  }
});
$(".habilitar").click(function(event) {
  var id=$(this).attr('user');
  $.get('index.php?r=usuario/habilitar',{id:id},function(data) {
    var res=$.parseJSON(data);
    if(res.success==true){
    console.log('ok');
    $("#usuario"+id).remove();
    }else{
     console.log('failed');
    }
  });
});
$(".eliminar").click(function(event) {
  var id=$(this).attr('user');
  $.get('index.php?r=usuario/eliminar',{id:id},function(data) {
    var res=$.parseJSON(data);
    if(res.success==true){
    console.log('ok');
    }else{
     console.log('failed');
    }
  });
});

JS;
$this->registerJs($script);
?>


