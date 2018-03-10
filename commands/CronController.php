<?php
/**
 * Created by PhpStorm.
 * User: Luisito Suarez
 * Date: 30/11/2017
 * Time: 22:12
 */

namespace app\commands;


use app\models\Foto;
use app\models\Notificaciones;
use app\models\Producto;
use app\models\Subasta;
use app\models\Usuario;
use Exception;
use Yii;
use yii\console\Controller;

class CronController extends Controller
{
    public function actionIndex(){
        $terminadas = Producto::findBySql('Select producto.* from producto join subasta on producto.id_producto=subasta.id_producto WHERE fecha_limite<=now() and subasta.terminada=false and subasta.notificada=false')->all();
       echo 'executedd';
        if (count($terminadas) > 0) {
            foreach ($terminadas as $terminada) {
                $rel_user = Usuario::findOne(['id_usuario' => $terminada->id_usuario]);

                $related_auction = Subasta::findOne(['id_producto' => $terminada->id_producto]);
                if ($related_auction->id_usuario != null && $related_auction->notificada == false) {
                    $win_user = Usuario::findOne(['id_usuario' => $related_auction->id_usuario]);
                    $notificacion = new Notificaciones();
                    $notificacion->usuario_id =  $related_auction->id_usuario;
                    $notificacion->nuevo = true;
                    $notificacion->nota = 'Usted ha ganado la subasta por el producto <a href="index.php?r=producto/view&id=' . $terminada->id_producto . '">' . $terminada->anuncio . '</a>, contacte con <a href="index.php?r=usuario/visit-profile&id_user=' . $rel_user->id_usuario . '">' . $rel_user->nom_user . '</a> para concretar la venta';
                    if($notificacion->save()){
                        echo "notificada1";
                    }

                    $notificacion2 = new Notificaciones();
                    $notificacion2->usuario_id = $terminada->id_usuario;
                    $notificacion2->nuevo = true;
                    $notificacion2->nota = 'Su subasta por el producto <a href="index.php?r=producto/view&id=' . $terminada->id_producto . '">' . $terminada->anuncio . '</a>,ha sido ganada,contacte con <a href="index.php?r=usuario/visit-profile&id_user=' . $win_user->id_usuario . '">' . $win_user->nom_user . '</a> para concretar la venta';
                    if($notificacion2->save()){
                        echo "notificada2";
                    }
                    $related_auction->notificada = true;
                    $related_auction->update();
                    // Subasta::updateAll(['notificada' => true], 'id_producto=' . $related_auction->id_producto);
//               $sub=new Subasta();
//                $sub->id_subasta=$related_auction->id_subasta;
//                $sub->actividad=$related_auction->actividad;
//                $sub->notificada=true;
//                $sub->id_usuario=$related_auction->id_usuario;
//                $sub->id_producto=$related_auction->id_producto;
//                $sub->terminada=$related_auction->terminada;
//                $sub->precio_actual=$related_auction->precio_actual;
//                $sub->save(false);
                    try {
                        Yii::$app->mailer->compose()
                            ->setTo([$win_user->correo])
                            ->setFrom([Yii::$app->params['adminEmail']])
                            ->setSubject('!!!!!FELICITACIONES: Subasta Ganada')
                            ->setTextBody('Usted ha ganado la subasta por el producto ' . $terminada->anuncio . ' por un precio final de ' . $related_auction->precio_actual . ', subastado por ' . $rel_user->nom_user . ', usted puede contactarlo para concretar la venta mediante el email ' . $rel_user->correo)
                            ->attach(Foto::findFirstProductFoto($terminada->id_producto)->url)
                            ->send();

                        Yii::$app->mailer->compose()
                            ->setTo([$rel_user->correo])
                            ->setFrom([Yii::$app->params['adminEmail']])
                            ->setSubject('!!!!!FELICITACIONES: Subasta Ganada')
                            ->setTextBody('Su subasta por el producto ' . $terminada->anuncio . ' ha concluido, con un precio final de ' . $related_auction->precio_actual . ', ganada por ' . $win_user->nom_user . ', usted puede contactarlo para concretar la venta mediante el email ' . $win_user->correo)
                            ->attach(Foto::findFirstProductFoto($terminada->id_producto)->url)
                            ->send();
                    } catch (Exception $e) {

                    }
                }
            }
        }
        $cantNotif = count(Notificaciones::findAll(['usuario_id' => Yii::$app->user->id, 'nuevo' => true]));
        die();
    }

}