<?php

use app\models\Subasta;
use app\models\Usuario;
use yii\helpers\Html;
use yii\i18n\Formatter;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Producto */
\app\assets\AppAsset::register($this);

$this->title = $model->anuncio;
$this->params['breadcrumbs'][] = ['label' => 'Detalles',];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="producto-view">

        <div class="main">
            <div class="producto">

                <div class="row single">
                    <div class="col-md-9">
                        <div class="single_left">
                            <div class="grid images_3_of_2">
                                <ul id="etalage">
                                    <?php
                                    $fotos = \app\models\Foto::findAll(['producto' => $model->id_producto]);
                                    foreach ($fotos as $foto) {
                                        echo '<li>';
                                        echo '<img class="etalage_thumb_image" src="' . $foto->url . '" class="img-responsive" />';
                                        echo ' <img class="etalage_source_image" src="' . $foto->url . '" class="img-responsive" title=""/>';
                                        echo '</li>';
                                    }
                                    ?>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="desc1 span_3_of_2">
                            <h3><?= Html::encode($this->title) ?></h3>
                            <p id="precio<?= $model->id_producto ?>">Precio:
                                <span>$<?= Subasta::findOne(['id_producto' => $model->id_producto])->precio_actual ?></span>
                            </p>
                            <h3>Vendedor:<span><a
                                        href="index.php?r=usuario/visit-profile&id_user=<?= $model->id_usuario ?>"><?= Usuario::findOne(['id_usuario' => $model->id_usuario])->nom_user ?></a></span>
                            </h3>
                            <a href="index.php?r=historialsubasta/view&id=<?= Subasta::findOne(['id_producto' => $model->id_producto])->id_subasta ?>">
                                <h3 id="actividad<?= $model->id_producto ?>">Cantidad de
                                    pujas: <?= Subasta::findOne(['id_producto' => $model->id_producto])->actividad ?></h3>
                            </a>
                            <h3>Fin de la
                                subasta: <?= DateTime::createFromFormat('Y-m-d H:i:s', $model->fecha_limite)->format('d/m/y h:i:s') ?></h3>
                            <div>
                                <?php
                                if (Yii::$app->user->id != $model->id_usuario) {
                                    echo '<form method="post" prod="' . $model->id_producto . '">';
                                    echo '<div class="form-group">';
                                    $minimo = Subasta::findOne(['id_producto' => $model['id_producto']])->precio_actual + 1;
                                    if (!Yii::$app->user->isGuest && Usuario::roleInArray(['Usuario'])) {

                                        echo '<input type="number" class="form-control"  name="puja' . $model->id_producto . '"  min="' . $minimo . '" id="puja' . $model->id_producto . '" placeholder="$' . $minimo . '" style="margin: 5px;">';
                                        //echo '<div class="col-sm-4>';
                                        echo '<button type="button" class=" push_button btn-submit" prod="' . $model->id_producto . '" style="margin: 5px; height:40px;"><span class=" glyphicon glyphicon-usd"></span><span class="glyphicon glyphicon-arrow-up"></span> Pujar</button>';

                                        if (\app\models\Subastaseguida::findOne(['subasta_id' => Subasta::findOne(['id_producto' => $model->id_producto]), 'usuario' => Yii::$app->user->id]) == null) {
                                            echo '<div style="height:40px;" class="btn-submit"><span class="glyphicon glyphicon-thumbs-up"><a id="follow" href="index.php?r=subastaseguida/follow-product&id=' . ($model->id_producto) . '" style="margin: 5px; color: white;">Seguir</a></span></div>';
                                        } else {

                                            echo '<div style="height:40px;" class="btn-submit"><span class="glyphicon glyphicon-thumbs-down"><a id="follow" href="index.php?r=subastaseguida/follow-product&id=' . $model->id_producto. '" style="margin: 5px; color:white;">Dejar de Seguir</a></span></div>';
                                        }

                                    } elseif (!Yii::$app->user->isGuest) {
                                        if (Usuario::roleInArray(['Administrador'])) {
                                            echo '<a class="btn btn-danger" href="index.php?r=producto/delete&id=' . Yii::$app->getSecurity()->encryptByKey($model->id_producto,Yii::$app->params['salt']) . '">Retirar producto</a>';
                                        }
                                    }
                                    echo '</div>';//fin form group
                                    echo '</form>';//fin form
                                    echo '</div>';//fin formulario
                                }
                                ?>
                            </div>

                        </div>
                        <div class="col-md-3">
                            <div class="w_sidebar">
                                <div class="w_nav1">
                                    <div class="product-timer btn-lg"
                                         fecha="<?= DateTime::createFromFormat('Y-m-d H:i:s', $model->fecha_limite)->format('Y/m/d H:i:s') ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="single-bottom1">
                    <h6>Descripcion:</h6>
                    <p class="prod-desc"><?= $model->descripcion ?></p>
                </div>
                <div class="single-bottom2" id="comentarios">
                    <h6>Comentarios:</h6>
                    <?php
                    $coments = \app\models\Comentario::findAll(['id_producto' => $model->id_producto]);
                    foreach ($coments as $coment) {
                        echo '<div class="row"><h3 class="col-sm-2">' . \app\models\Usuario::findOne(['id_usuario' => $coment->id_usuario])->nom_user . ': </h3><p class="col-sm-10">' . $coment->comentario . '</p></div>';
                    }
                    ?>
                </div>
                <?php
                if (!Yii::$app->user->isGuest) {
                    echo '<div class="row" id="post-coment">';
                    echo '<div class="form-group">';
                    echo '<div class="col-sm-9">';
                    echo '<input class="form-control" name="coment" id="coment" type="text" placeholder="Escriba su opinion sobre el producto">';
                    echo '</div>';
                    echo '<div class="col-sm-3">';
                    echo '<button class="btn btn-info boton-post " name="' . $model->id_producto . '" id="' . $model->id_producto . '" type="button">Post <span class="glyphicon glyphicon-send"></span></button>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
<?php
$script = <<<JS

jQuery(document).ready(function($){
// $('.retirar-prod').click(function(event) {
//   var id=$(this).attr('id');
//   if(id!=undefined){
//     $.get('index.php?r=producto/delete',{id:id},function(data) {
//      
//     })
//   }
// });
			$('#etalage').etalage({
					thumb_image_width: 300,
					thumb_image_height: 400,
					source_image_width: 900,
					source_image_height: 1200,
					show_hint: true			
				});

			});
$(function()
			{
				$('.scroll-pane').jScrollPane();
			});
JS;
$this->registerJs($script);
?>