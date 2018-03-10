<?php

namespace app\controllers;

use app\models\Foto;
use app\models\Notificaciones;
use app\models\Producto;
use app\models\SubastaSeguida;
use app\models\Tipo;
use app\models\Usuario;
use Yii;
use app\models\Subasta;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SubastaController implements the CRUD actions for Subasta model.
 */
class SubastaController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['push', 'register-sell'],
                'rules' => [
                    ['allow' => true, //Yii::$app->user->can('user'),
                        'actions' => ['push', 'register-sell'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rules, $action) {
                            $valid_roles = ['Usuario'];
                            return Usuario::roleInArray($valid_roles) && Usuario::isActive();
                        }
                    ],
                  
                ],
            ],

        ];
    }


    /**
     * Finds the Subasta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Subasta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Subasta::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPuch($idprod, $money)
    {
        $producto = Producto::findOne($idprod);
        if ($producto->id_usuario != Yii::$app->user->id) {
            $subasta = Subasta::findOne(['id_producto' => $idprod]);

            if ($money > $subasta->precio_actual && !$subasta->terminada) {
                $subasta->precio_actual = $money;
                if ($subasta->id_usuario != Yii::$app->user->id) {
                    if ($subasta->id_usuario != null) {
                        $notificacion = new Notificaciones();
                        $notificacion->usuario_id = $subasta->id_usuario;
                        $notificacion->nota = "Usted ya no encabeza la subasta del producto con anuncio: <a href='index.php?r=producto/view&id=" . $idprod . ">" . $producto->anuncio . "</a>";
                        $notificacion->nuevo = true;
                        $notificacion->save(false);
                    }
                    $subasta->id_usuario = Yii::$app->user->id;
                }

                $subasta->actividad = $subasta->actividad + 1;
                $subasta->save(false);
//                $subastaSeguida = Subastaseguida::findOne(['subasta_id' => $subasta->id_subasta, 'usuario' => Yii::$app->user->id]);
//                if ($subastaSeguida == null) {
//                    $subastaSeguida = new Subastaseguida();
//                    $subastaSeguida->subasta_id = $subasta->id_subasta;
//                    $subastaSeguida->usuario = Yii::$app->user->id;
//                    $subastaSeguida->save(false);
//                }
                $seguidas = SubastaSeguida::findAll(['subasta_id' => $subasta->id_subasta]);
                foreach ($seguidas as $seguida) {
                    $priceNotice = new Notificaciones();
                    $priceNotice->usuario_id = $seguida->usuario;
                    $priceNotice->nota = 'El precio de la subasta <a href="index.php?r=producto/view&id=' . $idprod . '>' . $producto->anuncio . '</a> ha subido a $' . $subasta->precio_actual;
                    $priceNotice->nuevo = true;
                    if ($subasta->id_usuario != $seguida->usuario) {
                        $priceNotice->save();
                        if (!Yii::$app->params['isOffline']) {
                            Yii::$app->mailer->compose()
                                ->setFrom(Yii::$app->params['adminEmail'])
                                ->setTo(Usuario::findOne(['id_usuario' => $subasta->id_usuario]))
                                ->setSubject('ExpressDealer: Hola, tienes ' . count(Notificaciones::findAll(['usuario_id' => Yii::$app->user->id])) . ' notificaciones nuevas')
                                ->setTextBody('Equipo de ExpressDealer')
                                ->send();
                        }
                    }
                }
                if (!Yii::$app->params['isOffline']) {
                    Yii::$app->mailer->compose()
                        ->setFrom(Yii::$app->params['adminEmail'])
                        ->setTo(Usuario::findOne(['id_usuario' => Yii::$app->user->id]))
                        ->setSubject('ExpressDealer: Hola, tienes ' . count(Notificaciones::findAll(['usuario_id' => Yii::$app->user->id])) . ' notificaciones nuevas')
                        ->setTextBody('Equipo de ExpressDealer')
                        ->send();
                }
                $type = Producto::find()->where(['id_producto' => $idprod])->limit(1)->all()[0]->tipo;
                $subType = Producto::find()->where(['id_producto' => $idprod])->limit(1)->all()[0]->sub_tipo;
                $result = array('idprod' => $idprod, 'type' => $type, 'subtype' => $subType, 'money' => $money, 'actividad' => $subasta->actividad);
                echo json_encode($result);
            }
        } else {
            echo json_encode(array('message' => 'true'));
        }
    }

    public function actionRegisterSell($id_prod)
    {
        $res = Subasta::updateAll(['terminada' => true], 'id_producto=' . $id_prod);
        if ($res > 0) {
            return json_encode(array('successfull' => true));
        } else {
            return json_encode(array('successfull' => false));
        }
    }

    public function actionNotifyWinners()
    {

        $terminadas = Producto::findBySql('Select producto.* from producto join subasta on producto.id_producto=subasta.id_producto WHERE fecha_limite<=now() and subasta.terminada=false and subasta.notificada=false')->all();
        if (count($terminadas) > 0) {
            foreach ($terminadas as $terminada) {
                $rel_user = Usuario::findOne(['id_usuario' => $terminada->id_usuario]);

                $related_auction = Subasta::findOne(['id_producto' => $terminada->id_producto]);
                if ($related_auction->id_usuario != null && $related_auction->notificada == false) {
                    $win_user = Usuario::findOne(['id_usuario' => $related_auction->id_usuario]);
                    $notificacion = new Notificaciones();
                    $notificacion->usuario_id = $related_auction->id_usuario;
                    $notificacion->nuevo = true;
                    $notificacion->nota = 'Usted ha ganado la subasta por el producto <a href="index.php?r=producto/view&id=' . $terminada->id_producto . '">' . $terminada->anuncio . '</a>, contacte con <a href="index.php?r=usuario/visit-profile&id_user=' . $rel_user->id_usuario . '">' . $rel_user->nom_user . '</a> para concretar la venta';
                    $notificacion->save();

                    $notificacion2 = new Notificaciones();
                    $notificacion2->usuario_id = $terminada->id_usuario;
                    $notificacion2->nuevo = true;
                    $notificacion2->nota = 'Su subasta por el producto <a href="index.php?r=producto/view&id=' . $terminada->id_producto . '">' . $terminada->anuncio . '</a>,ha sido ganada,contacte con <a href="index.php?r=usuario/visit-profile&id_user=' . $win_user->id_usuario . '">' . $win_user->nom_user . '</a> para concretar la venta';
                    $notificacion2->save();
                    $related_auction->notificada = true;
                    $related_auction->update();

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
        return json_encode(array('successfull' => true, 'cantNotif' => $cantNotif));
    }
}
