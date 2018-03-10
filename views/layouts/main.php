<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\models\Notificaciones;
use app\models\Subtipo;
use app\models\Tipo;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<html>
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

</head>
<body>
<?php $this->beginBody() ?>
<nav class="top_bg navbar-default" style="color: white;">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#" style="color: white;">...ubid, ubuy, uclose it</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav navbar-right" style="color: white;">
                <li style="color: white;"><a href="index.php"><span style="color: white;">Inicio</span></a></li>

                <li style="color: white;"><a href="index.php?r=site/about"><span
                            style="color: white;">Acerca de...</span></a></li>
                <?php
                if (!Yii::$app->user->isGuest) {
                    if (\app\models\Usuario::roleInArray(['Usuario'])) {
                        echo ' <li style="color: white;"><a href="index.php?r=site/contact"><span style="color: white;">Contactanos</span></a></li>';
                    }
                } else {
                    echo ' <li style="color: white;"><a href="index.php?r=site/contact"><span style="color: white;">Contactanos</span></a></li>';
                }
                ?>


                <li style="color: white;">

                    <?php
                    echo Yii::$app->user->isGuest ? (
                    '<a href="index.php?r=site/login"><span style="color: white;" class="glyphicon glyphicon-log-in"> Login</span></a>'
                    ) : (
                        '<a href="index.php?r=site/logout"><span style="color: white;" class="glyphicon glyphicon-log-out"> Logout (' . Yii::$app->user->identity->nom_user . ')</span></a>'
                    );
                    ?>

                </li>
                <?php
                if (!Yii::$app->user->isGuest) {
                    //echo '|';
                    echo '<li style="color: white;"><a style="padding-right: 7px;" href="index.php?r=usuario/perfil"><span class="glyphicon glyphicon-user" style="color: white;"> Perfil <span></a></li>';
                    if (\app\models\Usuario::roleInArray(['Usuario'])) {
                        echo '<li ><a style="padding-top: 8px; padding-left: 0px;" href="index.php?r=notificaciones/index"><span id="notificacionesNuevas" class=" btn-warning badge">' . count(Notificaciones::findAll(['usuario_id' => Yii::$app->user->id, 'nuevo' => true])) . '</span></a></li>';
                    }
                }
                ?>
            </ul>
        </div>
    </div>
</nav>
<div class="wrap">
    <div class="header_bg">
        <div class="container-fluid">
            <div class="header">
                <div class="logo">
                    <a href="index.php"><img style="height: 80px;"
                                             src="<?= $this->assetManager->baseUrl ?>/images/logo.png"
                                             alt=""/> </a>
                </div>
                <!-- start header_right -->
                <div class="header_right">
                    <div class="create_btn" style="float:right;">
                        <?php
                        if ((Yii::$app->user->isGuest)) {
                            echo '<a id="registrate" class="btn-warning" href="index.php?r=usuario%2Fcreate" style="padding-left: 7px;"><span style="padding-left: 1px;" class="glyphicon glyphicon-user"></span> Registrate</a>';
                        } else {
                            if (\app\models\Usuario::roleInArray(['Usuario'])) {
                                echo '<a class=" btn btn-submit btn-sm" href="index.php?r=producto%2Fcreate" style="padding-left: 7px;"><span style="padding-left: 1px;" class="glyphicon glyphicon-upload"></span> Subasta ya!!!</a>';
                            } elseif (\app\models\Usuario::roleInArray(['Administrador'])) {
                                echo '<a class="btn-submit" href="index.php?r=usuario%2Fcreate-admin" style="padding-left: 7px;"><span style="padding-left: 1px;" class="glyphicon glyphicon-king"></span> Crear Administrador</a>';
                            }
                        }
                        ?>
                    </div>
                    <ul class="icon1 sub-icon1 profile_img">
                        <li><?= Yii::$app->user->isGuest ? '<a class="active-icon c2" href="index.php?r=site/login"></a>' : '<a class="active-icon c2" href="index.php?r=producto/subastados&page=-1"></a>' ?>
                            <ul class="sub-icon1 list">
                                <li><h3>Articulos
                                        Subastados</h3><?= Yii::$app->user->isGuest ? '<a href="index.php?r=site/login"></a>' : '<a  href="index.php?r=producto/subastados&page=-1"></a>' ?>
                                </li>
                                <li>
                                <li><?= Yii::$app->user->isGuest ? '<p>Si no encuentras tus subastas, <a href="index.php?r=site/login">inicia sesion</a> para verlas</p>' : '<p>Aqui encontraras tus subastas</p>' ?></li>

                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="icon1 sub-icon1 profile_img">
                        <li><?= Yii::$app->user->isGuest ? '<a class="active-icon c1" href="index.php?r=site/login"></a>' : '<a class="active-icon c1" href="index.php?r=producto/seguidos&page=-1"></a>' ?>
                            <ul class="sub-icon1 list">

                                <li><h3>Subastas
                                        Seguidas</h3><?= Yii::$app->user->isGuest ? '<a href="index.php?r=site/login"></a>' : '<a  href="index.php?r=producto/seguidos&page=-1"></a>' ?>
                                </li>
                                <li><?= Yii::$app->user->isGuest ? '<p>Si no encuentras tus subastas favoritas, <a href="index.php?r=site/login">inicia sesion</a> para verlas</p>' : '<p>Aqui encontraras las subastas que has estado siguiendo</p>' ?></li>
                            </ul>
                        </li>
                    </ul>

                    <div class="search">
                        <input type="text" value="" name="filter_result" id="filter_result" placeholder="search...">
                        <input name="search_btn" id="search_btn" type="submit" value=""/>
                    </div>

                    <div class="clearfix">

                    </div>
                </div>
                <!-- start header menu -->
                <ul class="megamenu skyblue" id="shop_menu">
                    <?php
                    $tipos = Tipo::find()->all();
                    $i = 2;
                    foreach ($tipos as $item) {
                        echo '<li><a href="index.php?r=producto/get-products-by-type&type=' . $item->id_tipo . '&page=1" class="tipo_element color' . $i . '" tipo="' . $item->id_tipo . '">' . $item->nom_tipo . '</a><div class="megapanel">';
                        //aqui empieza el megapanel
                        $types = Tipo::find()->all();
                        echo '<div class="row" style="text-align: left;">';
                        $size = round(12 / count($types)) - 1;
                        if ($size == 0) $size = 1;
                        foreach ($types as $typeItem) {
                            $subtypes = Subtipo::find()->where(['id_tipo' => $typeItem->id_tipo])->all();
                            echo '<div class="col1" style="margin-top: 10px;"><div class="h_nav"><h4>' . $typeItem->nom_tipo . '</h4>';
                            echo '<ul>';
                            foreach ($subtypes as $subtype) {
                                echo '<li><a class="subtipo_element" tipo="' . $typeItem->id_tipo . '" subtipo="' . $subtype->id_sub_tipo . '" href="index.php?r=producto/get-products-by-subtype&type=' . $subtype->id_sub_tipo . '&page=1" >' . $subtype->sub_tipo . '</a></li>';
                            }
                            echo '</ul>';
                            echo '</div>';
                            echo '</div>';
                        }
                        echo '</div>';

                        //aqui se acaba
                        echo '</div></li>';
                        //echo '<li>'.Html::a($item->nom_tipo, ['subasta/get-products-by-subtype', 'type' =>$item->id_tipo],['class'=>'color'.$i]).'<div class="megapanel"></li>';
                        $i++;
                        if ($i > 9) {
                            $i = 2;
                        }
                    }

                    echo '<li><a href="index.php?r=producto/search" class="color' . $i . '">Todas</a><div class="megapanel">';
                    echo '<div class="row" style="text-align: left;">';
                    //$size = round(12 / count($types)) - 1;
                    //if ($size == 0) $size = 1;
                    foreach ($types as $typeItem) {
                        $subtypes = Subtipo::find()->where(['id_tipo' => $typeItem->id_tipo])->all();
                        echo '<div class="col1" style="margin-top: 10px;"><div class="h_nav"><h4>' . $typeItem->nom_tipo . '</h4>';
                        echo '<ul>';
                        foreach ($subtypes as $subtype) {
                            echo '<li><a class="subtipo_element" tipo="' . $typeItem->id_tipo . '" subtipo="' . $subtype->id_sub_tipo . '" href="index.php?r=producto/get-products-by-subtype&type=' . $subtype->id_sub_tipo . '&page=1" >' . $subtype->sub_tipo . '</a></li>';
                        }
                        echo '</ul>';
                        echo '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
                    echo '</div></li>';

                    ?>

                </ul>
            </div>
        </div>
    </div>
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>


<div class="footer" style="margin-top: 30px">
    <div class="container">
        <div class="copy">
            <p class="link">&copy; Todos los derechos reservados | Diseñado por: &nbsp; <a href="">Alejandro
                    Barthelemy</a>&nbsp;|&nbsp;<a href="">Alejandro Rivera</a>&nbsp;|&nbsp;<a href="">Luis Suarez</a>&nbsp;|&nbsp;<a
                    href="">Alejandro Vidaurrazaga</a></p>
            <p class="link">Para ver el reglamento pulse <a href="">Aqui</a></p>

        </div>
    </div>
</div>
<?php $this->endBody() ?>

</body>
</html>
<?php
$this->endPage() ?>
