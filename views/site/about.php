<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Acerca de...';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
<div class="container-fluid">
    <p>
      ExpressDealer es el sitio web que has buscado todo este tiempo para llevar a cabo tus subastas. Cuenta con una
        variada seleccion de productos que puedes comprar o subastar, la eleccion es tuya. Nuestro equipo de desarrollo
        garantiza que tu informacion personal esta totalmente protegida y solo seran utilizadas para llevar a cabo las transacciones del sitio.

        Busca, compara, y haznos saber tus dudas, inquietudes, y sugerencias para mejorar tu experiencia como usuario de ExpressDealer.
        <a href="index.php?r=site/contact">Por favor, contactanos!!!!</a>
    </p>
</div>
         <div class="container-fluid" style="margin-top:20px;">
             <div class="col-md-4">
                <h3>Desarrolladores:</h3>
                <ul>
                    <li>Alejandro Barthelemy</li>
                    <li>Alejandro Rivera</li>
                    <li>Luis Suarez</li>
                    <li>Alejandro Vidaurrazaga</li>
                </ul>
             </div>
             <div class="col-md-8" style="padding: 20px;">
                 <img src='images/logo.png' alt="Equipo de desarrolladores" width="40%" height="120">
             </div>
        </div>
</div>
