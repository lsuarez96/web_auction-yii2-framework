<?php
//assetManager->baseUrl
/* @var $this yii\web\View */

use app\assets\AppAsset;
use app\models\Foto;
use app\models\Producto;

$this->title = 'ExpressDealer';
AppAsset::register($this);
?>
    <div class="container">
        <div class="main">

            <div class="row content_top">

                <div class="col-md-9 content_left">
                    <!-- start slider -->
                    <div id="fwslider">

                    <div class="slider_container">

                        <!--/slide -->
                        <?php
                        $latestProducts = Producto::findLastProducts();
                        foreach ($latestProducts as $latestProduct) {
                            $image = Foto::findFirstProductFotoSlide($latestProduct->id_producto);
                            echo '<div class="slide">';
                            echo '<a href="index.php?r=producto/view&id=' . $latestProduct->id_producto . '">';
                            if ($image != null)
                                echo '<img src="' . $image->url . '" height="500" width="100%" alt=""/></a></div>';
                        }
                        ?>
                    </div>
                    <div class="timers"></div>
                    <div class="slidePrev"><span
                            style='background-image: url("<?= $this->assetManager->baseUrl . "/images/arrows.png" ?>")'></span>
                    </div>
                    <div class="slideNext"><span
                            style='background-image: url("<?= $this->assetManager->baseUrl . "/images/arrows.png" ?>")'></span>
                    </div>
                </div>

                    <!-- end  slider -->
                </div>
                <?php
                $prods = Producto::findProductsWithMoreActivity();
              //  var_dump($prods);
               // die();
                $count = 0;
                echo '<div class="col-md-3 sidebar">';
                foreach ($prods as $prod) {
                    $foto = Foto::findFirstProductFoto($prod->id_producto);
                    if ($count < 3) {
                        echo '<div class="grid_list">';
                        echo '<a href="index.php?r=producto/view&id=' . $prod['id_producto'] . '">';
                        if($count == 1){
                            echo'<div class="grid_text-middle">';
                            $anuncio=$prod['anuncio'];
                            $anuncio=substr($anuncio,0,40);
                            $anuncio.='...';
                            echo '<h3><a href="index.php?r=producto/view&id=' . $prod['id_producto'] . '">' . $anuncio . '</a></h3>';
                            $descripcion=$prod['anuncio'];
                            $descripcion=substr($descripcion,0,60);
                            $descripcion.='...';
                            echo '<p>' . $descripcion . '</p>';
                            echo '</div>';
                            echo '<div class="grid_img last">';
                            echo '<img src="' . $foto->url . '" class="img-responsive" alt="" style="width: 88px; height: 97px;"/>';
                            echo '</a>';
                            echo '</div>';
                            echo '<div class="clearfix"></div>';
                        }else{
                            echo '<div class="grid_img">';
                            echo '<img src="' . $foto->url . '" class="img-responsive" alt="" style="width: 88px; height: 97px;"/>';
                            echo '</div>';
                            echo '<div class="grid_text left">';
                            $anuncio=$prod['anuncio'];
                            $anuncio=substr($anuncio,0,40);
                            $anuncio.='...';
                            echo '<h3><a href="index.php?r=producto/view&id=' . $prod['id_producto'] . '">' . $anuncio . '</a></h3>';
                            $descripcion=$prod['anuncio'];
                            $descripcion=substr($descripcion,0,60);
                            $descripcion.='...';
                            echo '<p>' . $descripcion . '</p>';
                            echo '<div class="clearfix"></div>';
                            echo '</a>';
                            echo '</div>';
                        }
                        echo '</div>';
                        $count++;
                    }

                }
                if ($count == 3) {
                    echo '</div>';
                }
                ?>
            </div>
            <div class="content col-md-9">
                <div class="content_text">
                    <h4><a>Subastas recientes</a></h4>
                </div>
                   <?php
                   $latestProducts = Producto::findLastProducts();
                   $count = 0;
                   echo '<div class="row grids">';
                   foreach ($latestProducts as $latestProduct) {
                       $image = Foto::findFirstProductFoto($latestProduct->id_producto);
                       if($count < 8){
                           echo '<div class="col-md-3 grid1" style="margin-top:20px;">';
                           echo '<a href="index.php?r=producto/view&id=' . $latestProduct->id_producto . '">';
                           echo '<img src="' . $image->url.'" class="img-responsive" style="width: 270px; height: 350px;" alt=""/>';
                           echo '<div class="look">';
                           echo '<h4>New</h4>';
                           echo '<p>Ver detalles</p>';
                           echo '</div></a>';
                           echo '</div>';
                           $count++;
                       }
                       else
                           return;
                   }
                       echo '</div>';
                   ?>


            </div>
        </div>
    </div>


<?php
$this->registerJsFile(Yii::$app->homeUrl . '/js/jquery-1.11.1.min.js');
$this->registerJsFile(Yii::$app->homeUrl . '/js/lsg.script.js');

?>