<?php

namespace app\controllers;

use app\models\FilterProduct;
use app\models\Foto;
use app\models\SearchProducto;
use app\models\Subasta;
use app\models\User;
use app\models\Usuario;

use DateTime;

use Yii;
use app\models\Producto;
use yii\base\Exception;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\mysql\QueryBuilder;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\models\ImageFiles;

/**
 * ProductoController implements the CRUD actions for Producto model.
 */
class ProductoController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['POST'],
//                ],
//            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'update', 'delete'],
                'rules' => [
                    ['allow' => true, //Yii::$app->user->can('user'),
                        'actions' => ['create'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rules, $action) {
                            $valid_roles = ['Usuario'];
                            return Usuario::roleInArray($valid_roles) && Usuario::isActive();
                        }
                    ],
                    ['allow' => true, //Yii::$app->user->can('user'),
                        'actions' => ['update', 'delete'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rules, $action) {
                            $valid_roles = ['Administrador'];
                            return Usuario::roleInArray($valid_roles) && Usuario::isActive();
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Producto models.
     * @return mixed
     */
    public function actionIndex()
    {
//Producto::find()->where(['id_usuario' => Yii::$app->user->id]),
        return $this->render('index', [
            'dataProvider' => Producto::findBySql("Select producto.* from producto join subasta on producto.id_producto=subasta.id_producto WHERE  subasta.terminada=false AND producto.fecha_limite>now() order by producto.id_producto DESC limit 10")->all(),
            'filter' => ''
        ]);
    }

    public function actionSearch()
    {
        $searchModel = new SearchProducto();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('filter', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Producto model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    /**
     * Creates a new Producto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        if (!Yii::$app->user->isGuest) {
            $model = new Producto();
            $images = new ImageFiles(['scenario' => 'article']);
            $images->file = UploadedFile::getInstances($images, 'file');
            $model->id_usuario = Yii::$app->user->id;


            if ($model->load(Yii::$app->request->post()) && $model->saveProduct($images)) {
                $id = Producto::find()->orderBy(['id_producto' => SORT_DESC])->where(['id_usuario' => Yii::$app->user->id])->limit(1)->one()->id_producto;
                return $this->actionView($id);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'images' => $images,
                ]);
            }
        }
        return $this->redirect(['site/login']);
    }


    /**
     * Updates an existing Producto model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $images = new ImageFiles(['scenario' => 'article']);
        $images->file = UploadedFile::getInstances($images, 'file');
        if ($model->load(Yii::$app->request->post()) && $model->saveProduct($images)) {
            return $this->redirect(['view', 'id' => $model->id_producto]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'images' => $images,
            ]);
        }
    }

    /**
     * Deletes an existing Producto model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $id=Yii::$app->security->decryptByKey($id,Yii::$app->params['salt']);
        $model = $this->findModel($id);
        if($model!=null) {
            $user = Usuario::findOne(['id_usuario' => $model->id_usuario]);
            try {
                if (!Yii::$app->params['isOffline']) {
                    Yii::$app->mailer->compose()
                        ->setFrom(Yii::$app->params['adminEmail'])
                        ->setTo($user->correo)
                        ->setSubject('Uno de sus productos ha sido retirado')
                        ->setTextBody("Uno de sus productos ha sido retirado por no cumplir con las exigencias y normas del sitio")
                        ->send();
                }
            } catch (Exception $e) {

            }
            $this->findModel($id)->delete();
        }
        return $this->redirect(['producto/scrollall']);
    }

    /**
     * Finds the Producto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Producto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Producto::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetProductsByType($type, $page)
    {

        $sql = 'SELECT producto.* FROM producto JOIN subasta ON producto.id_producto=subasta.id_producto  WHERE  producto.tipo=' . $type . ' and fecha_limite>now() ORDER BY producto.id_producto DESC';
        if ($page == 0) {
            $page = 1;
        }
        $initial = $page == 1;
        $perPage = 10;
        $start = ($page - 1) * $perPage;
        if ($start < 0) $start = 0;
        $query = $sql . " limit " . $start . "," . $perPage;
        $resultSet = Producto::findBySql($query)->all();

        if (!empty($resultSet) && !$initial) {
            $count = 0;
            foreach ($resultSet as $item) {
                if ($count % 4 == 0) {
                    echo '<div class="grids_of_4">';
                }
                $foto_url = \app\models\Foto::findFirstProductFoto($item->id_producto)->url;
                $subasta_relacionada = Subasta::findOne(['id_producto' => $item['id_producto']]);
                echo '<div class="grid1_of_4 pagina producto" typ="' . $item->tipo . '" page="' . $page . '">';
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
                if ($count == 5 || $count == 9 || $count == 12) {
                    echo '</div>';//cierre de la grilla de cuatro
                }
                $count++;
            }

//            foreach ($resultSet as $item) {
//                $subasta_relacionada = Subasta::findOne(['id_producto' => $item['id_producto']]);
//                echo '<div index="' . $item['id_producto'] . '" class="pagina producto" typ="' . $item['tipo'] . '" page="' . $page . '">';
//                echo '<button class="row col-sm-12" style="text-align:left; margin:5px;" data-toggle="collapse" data-target="#collapse' . $item['id_producto'] . '"><b>$' . Subasta::findOne(['id_producto' => $item['id_producto']])->precio_actual . '</b> ' . $item['anuncio'] . '</button>';
//                echo '<div class="row collapse ' . $item['id_producto'] . '" style=" margin:15px; box-shadow: 1px 1px 1px 1px rgba(0,0,0,0.4); padding: 15px;" id=collapse' . $item['id_producto'] . '>';//contenedor del producto
//                echo '<div class="row info-pujar">';//contenedor de la informacion y el formulario de pujar
//                echo '<div class="col-sm-9 information">';//contenedor de la informacion
//                echo '<h4>Subastado por:<a href="index.php?r=usuario/visit-profile&id_user=' . $item['id_usuario'] . '"><span>' . \app\models\Usuario::find()->where(['id_usuario' => $item['id_usuario']])->limit(1)->all()[0]->nom_user . '</span></a></h4>';
//                echo '<h4>Anuncio: <span>' . $item['anuncio'] . '</span></h4>';
//                echo '<h4 class="precio' . $item['id_producto'] . '" >Precio actual: <span>$' . Subasta::findOne(['id_producto' => $item['id_producto']])->precio_actual . '</span></h4>';
//                echo '<h4>Fin de la subasta: ' . $item['fecha_limite'] . '</h4>';
//                echo '<h4>Lider de la subasta: ' . Usuario::findOne(['id_usuario' => $subasta_relacionada->id_usuario])->nom_user . '</h4>';
//                echo '<h4 class="actividad' . $item['id_producto'] . '">Actividad del producto: ' . $subasta_relacionada->actividad . ' pujas</h4>';
//                echo '<h4><span>Descripcion:</span></h4>';
//                echo '<p>' . $item['descripcion'] . '</p>';
//                echo '</div>';//fin informacion
//                echo '<div class="col-sm-3 formulario">';
//                echo '<div style="margin-bottom:20px;" class="product-timer" fecha="' . DateTime::createFromFormat('Y-m-d H:i:s', $item->fecha_limite)->format('Y/m/d H:i:s') . '" ></div>';
//
//                echo '<form  prod="' . $item['id_producto'] . '">';
//                echo '<div style="margin-top: 30px;" class="form-group">';
//                $minimo = Subasta::findOne(['id_producto' => $item['id_producto']])->precio_actual + 1;
//                if (!Yii::$app->user->isGuest) {
//                    echo '<div class="col-sm-7">';
//                    echo '<input type="number" class="form-control"  name="precio-puja"  min="' . $minimo . '" id="puja' . $item['id_producto'] . '" placeholder="$' . $minimo . '" style="margin: 5px;">';
//                    echo '</div>';
//                    //echo '<div class="col-sm-4>';
//                    echo '<input type="button" value="Pujar" class="btn btn-danger" prod="' . $item['id_producto'] . '" style="margin: 5px;">';
//                }  // echo '</div>';
//                echo '</div>';//fin form group
//                echo '</form>';//fin form
//                echo '</div>';//fin formulario
//
//                echo '</div>';//fin formulario e informacion
//                echo Html::a('Mas Detalles>>', 'index.php?r=producto/view&id=' . $item['id_producto'], ['method=>get']);
//                echo '</div>';
//                echo '</div>';
//            }
        } else if ($initial) {
            return $this->render('productotipo', ['resultSet' => $resultSet, 'idTipo' => $type]);
        }
    }

    public function actionGetProductsBySubtype($type, $page)
    {

        $sql = 'SELECT producto.* FROM producto JOIN subasta ON producto.id_producto=subasta.id_producto  WHERE  producto.sub_tipo=' . $type . ' and fecha_limite>now() ORDER BY producto.id_producto DESC';
        if ($page == 0) {
            $page = 1;
        }
        $initial = $page == 1;
        $perPage = 10;
        $start = ($page - 1) * $perPage;
        if ($start < 0) $start = 0;
        $query = $sql . " limit " . $start . "," . $perPage;
        $resultSet = Producto::findBySql($query)->all();

        //$resultSet = Producto::findBySql($query)->all();
        // var_dump(count(Producto::findAll(['tipo'=>$type])));

//        $query = $query = 'SELECT producto.* FROM producto JOIN subasta ON producto.id_producto=subasta.id_producto  WHERE subasta.terminada=false AND producto.sub_tipo=' . $type . ' ORDER BY producto.id_producto DESC';
//        $resultSet = Producto::findBySql($query)->all();
//        $db =mysqli_connect('localhost','root','','subasta_web');
//        $resultSet=mysqli_query($db,$query);
        // if ($resultSet != null) {
        //return $this->render('productosubtipo', ['resultSet' => $resultSet, 'id_sub' => $type]);
        // }
        if (!empty($resultSet) && !$initial) {
            $count = 0;
            foreach ($resultSet as $item) {
                if ($count % 4 == 0) {
                    echo '<div class="grids_of_4">';
                }
                $foto_url = \app\models\Foto::findFirstProductFoto($item->id_producto)->url;
                $subasta_relacionada = Subasta::findOne(['id_producto' => $item['id_producto']]);
                echo '<div class="grid1_of_4 pagina producto" subtyp="' . $item->sub_tipo . '" page="' . $page . '">';
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
                if ($count == 5 || $count == 9 || $count == 12) {
                    echo '</div>';//cierre de la grilla de cuatro
                }
                $count++;

//                $subasta_relacionada = Subasta::findOne(['id_producto' => $item['id_producto']]);
//                echo '<div index="' . $item['id_producto'] . '" subtyp="' . $item['sub_tipo'] . '" class="pagina producto" page="' . $page . '">';
//                echo '<button class="row col-sm-12 " style="text-align:left" data-toggle="collapse" data-target="#collapse' . $item->id_producto . '"><b>$' . Subasta::findOne(['id_producto' => $item['id_producto']])->precio_actual . ' Anuncio:<b> ' . $item->anuncio . '</button>';
//                echo '<div class="row collapse' . $item['id_producto'] . '" style="box-shadow: 1px 1px 1px 1px rgba(0,0,0,0.4); padding: 15px; margin-top: 10px;" id=collapse' . $item->id_producto . '>';//contenedor del producto
//                echo '<div class="row info-pujar">';//contenedor de la informacion y el formulario de pujar
//                echo '<div class="col-sm-9 information">';//contenedor de la informacion
//                echo '<h4>Subastado por:<a href="index.php?r=usuario/visit-profile&id_user=' . $item['id_usuario'] . '"><span>' . \app\models\Usuario::find()->where(['id_usuario' => $item['id_usuario']])->limit(1)->all()[0]->nom_user . '</span></a></h4>';
//                echo '<h4>Anuncio: <span>' . $item['anuncio'] . '</span></h4>';
//                echo '<h4 "precio' . $item['id_producto'] . '" >Precio actual:<span>$' . Subasta::findOne(['id_producto' => $item['id_producto']])->precio_actual . '</span></h4>';
//                echo '<h4>Fin de la subasta: ' . $item['fecha_limite'] . '</h4>';
//                echo '<h4>Lider de la subasta: ' . Usuario::findOne(['id_usuario' => $subasta_relacionada->id_usuario])->nom_user . '</h4>';
//                echo '<h4 class="actividad' . $item['id_producto'] . '".>Actividad del producto: ' . $subasta_relacionada->actividad . ' pujas</h4>';
//                echo '<h4><span>Descripcion:</span></h4>';
//                echo '<p>' . $item['descripcion'] . '</p>';
//                echo '</div>';//fin informacion
//                echo '<div class="col-sm-3 formulario">';
//                echo '<div class="product-timer" fecha="' . DateTime::createFromFormat('Y-m-d H:i:s', $item->fecha_limite)->format('Y/m/d H:i:s') . '" ></div>';
//                echo '<form method="post" prod="' . $item->id_producto . '">';
//                echo '<div class="form-group">';
//                $minimo = Subasta::findOne(['id_producto' => $item['id_producto']])->precio_actual + 1;
//                if (!Yii::$app->user->isGuest) {
//                    echo '<div class="col-sm-7">';
//                    echo '<input type="number" class="form-control"  name="precio-puja"  min="' . $minimo . '" id="' . $item->id_producto . '" placeholder="$' . $minimo . '" style="margin: 5px;">';
//                    echo '</div>';
//                    //echo '<div class="col-sm-4>';
//                    echo '<input type="button" value="Pujar" class="btn btn-danger" prod="' . $item->id_producto . '" style="margin: 5px;">';
//                } // echo '</div>';*
//                echo '</div>';//fin form group
//                echo '</form>';//fin form
//                echo '</div>';//fin formulario
//
//                echo '</div>';//fin formulario e informacion
//                echo Html::a('Mas Detalles>>', 'index.php?r=producto/view&id=' . $item['id_producto'], ['method=>get']);
//                echo '</div>';
//                echo '</div>';
            }
        } else if ($initial) {
            return $this->render('productosubtipo', ['resultSet' => $resultSet, 'id_sub' => $type]);
        }
    }

    public function actionFilterByText($filter_result)
    {
        $filter_result = Html::encode($filter_result);
        return $this->render('index', [
            'dataProvider' => Producto::findBySql('Select producto.* FROM  producto JOIN subasta ON subasta.id_producto=producto.id_producto WHERE subasta.terminada=false AND producto.fecha_limite>now() AND producto.anuncio LIKE "%' . $filter_result . '%" OR producto.descripcion LIKE "%' . $filter_result . '%" ORDER BY producto.id_producto DESC limit 10')->all(),
            'filter' => $filter_result,
        ]);

    }


    public function actionGetProductsFilter($filter, $page)
    {

        $sql = 'Select producto.* FROM  producto JOIN subasta ON subasta.id_producto=producto.id_producto WHERE subasta.terminada=false AND producto.fecha_limite>now() AND producto.anuncio LIKE "%' . $filter . '%" OR producto.descripcion LIKE "%' . $filter . '%" ORDER BY producto.id_producto DESC';
        if ($page == 0) {
            $page = 1;
        }
        $perPage = 10;
        $start = ($page - 1) * $perPage;
        if ($start < 0) $start = 0;
        $query = $sql . " limit " . $start . "," . $perPage;
        $resultSet = Producto::findBySql($query)->all();
        // var_dump($query);
        // var_dump($start);
        //var_dump($perPage);
        if (!empty($resultSet)) {
            foreach ($resultSet as $item) {
                $subasta_relacionada = Subasta::findOne(['id_producto' => $item['id_producto']]);
                echo '<div index="' . $item['id_producto'] . ' filter="' . $filter . '" class="pagina" page="' . $page . '">';
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

                echo '<form  prod="' . $item['id_producto'] . '">';
                echo '<div class="form-group">';
                $minimo = Subasta::findOne(['id_producto' => $item['id_producto']])->precio_actual + 1;
                if (!Yii::$app->user->isGuest) {
                    echo '<div class="col-sm-7">';
                    echo '<input type="number" class="form-control"  name="precio-puja"  min="' . $minimo . '" id="puja' . $item['id_producto'] . '" placeholder="$' . $minimo . '" style="margin: 5px;">';
                    echo '</div>';
                    //echo '<div class="col-sm-4>';
                    echo '<input type="button" value="Pujar" class="btn btn-danger" prod="' . $item['id_producto'] . '" style="margin: 5px;">';
                }  // echo '</div>';
                echo '</div>';//fin form group
                echo '</form>';//fin form
                echo '</div>';//fin formulario

                echo '</div>';//fin formulario e informacion
                echo Html::a('Mas Detalles>>', 'index.php?r=producto/view&id=' . $item['id_producto'], ['method=>get']);
                echo '</div>';
                echo '</div>';
            }
        }
    }

    public function actionSeguidos($page)
    {

        $initial = false;
        if ($page == -1) {
            $initial = true;
        }

        $perPage = 10;
        $sql = 'Select producto.* from producto join subasta on subasta.id_producto=producto.id_producto join subasta_seguida on subasta.id_subasta=subasta_seguida.subasta_id WHERE subasta_seguida.usuario=' . Yii::$app->user->id . ' and producto.fecha_limite>now() Order By subasta_seguida.id_subasta_seguida DESC';
        $start = ($page - 1) * $perPage;
        if ($start < 0) $start = 0;
        $query = $sql . " limit " . $start . "," . $perPage;
        $resultSet = Producto::findBySql($query)->all();
        if (!empty($resultSet) && !$initial) {
            $count = 0;
            foreach ($resultSet as $item) {
                if ($count % 4 == 0) {
                    echo '<div class="grids_of_4">';
                }
                $foto_url = \app\models\Foto::findFirstProductFoto($item->id_producto)->url;
                $subasta_relacionada = Subasta::findOne(['id_producto' => $item['id_producto']]);
                echo '<div class="grid1_of_4 pagina producto" page="' . $page . '">';
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
                if ($count == 5 || $count == 9 || $count == 12) {
                    echo '</div>';//cierre de la grilla de cuatro
                }
                $count++;
            }
        } elseif (empty($resultSet) && $initial) {
            return $this->render('seguidos', []);
            //echo '<div class="load-info container-fluid" id="no-more-data" style="height: 40px; width: 100%; text-align: center; margin-top: 40px;  display: inline-block;"><div class=" alert alert-info"><p >No hay mas informacion para ser cargada</p></div></div>';
        } elseif ($initial) {
            return $this->render('seguidos', []);
        }
        //return $this->render('seguidos');
    }

    public function actionSubastados($page)
    {

        $initial = false;
        if ($page == -1) {
            $initial = true;
        }

        $perPage = 10;
        $sql = 'Select producto.* from producto WHERE producto.id_usuario=' . Yii::$app->user->id . ' Order By id_producto DESC';
        $start = ($page - 1) * $perPage;
        if ($start < 0) $start = 0;
        $query = $sql . " limit " . $start . "," . $perPage;
        $resultSet = Producto::findBySql($query)->all();
        if (!empty($resultSet) && !$initial) {
            $count = 0;
            foreach ($resultSet as $item) {
                if ($count % 4 == 0) {
                    echo '<div class="grids_of_4">';
                }
                $foto_url = \app\models\Foto::findFirstProductFoto($item->id_producto)->url;
                $subasta_relacionada = Subasta::findOne(['id_producto' => $item['id_producto']]);
                echo '<div class="grid1_of_4 pagina producto" page="' . $page . '">';
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
                if ($count == 5 || $count == 9 || $count == 12) {
                    echo '</div>';//cierre de la grilla de cuatro
                }
                $count++;
            }
        } elseif (empty($resultSet) && $initial) {
            return $this->render('subastados', []);
            //echo '<div class="load-info container-fluid" id="no-more-data" style="height: 40px; width: 100%; text-align: center; margin-top: 40px;  display: inline-block;"><div class=" alert alert-info"><p >No hay mas informacion para ser cargada</p></div></div>';
        } elseif ($initial) {
            return $this->render('subastados', []);
        }
    }

    public function actionScrollall($page=-1)
    {
        $initial = false;
        if ($page == -1) {
            $initial = true;
        }
        $perPage = 10;

        $sql = 'Select producto.* from producto join subasta on subasta.id_producto=producto.id_producto  WHERE producto.fecha_limite>now() Order By producto.id_producto DESC';
        $start = ($page - 1) * $perPage;
        if ($start < 0) $start = 0;
        $query = $sql . " limit " . $start . "," . $perPage;
        $resultSet = Producto::findBySql($query)->all();
        if (!empty($resultSet) && !$initial) {
            $count = 0;
            foreach ($resultSet as $item) {
                if ($count % 4 == 0) {
                    echo '<div class="grids_of_4">';
                }
                $foto_url = \app\models\Foto::findFirstProductFoto($item->id_producto)->url;
                $subasta_relacionada = Subasta::findOne(['id_producto' => $item['id_producto']]);
                echo '<div class="grid1_of_4 pagina producto" page="' . $page . '">';
                echo '<div class="content_box">';
                echo '<a href="index.php?r=producto/view&id=' . $item['id_producto'] . '">';
                echo '<div class="view view-fifth ">';
                echo ' <img src="' . $foto_url . '" class="img-responsive" alt="" height="240" style="height:240px; width:100%;"/>';
                //echo '<div class="mask"><span class="info">'.$subasta_relacionada->precio_actual.'</span></div>';
                echo '</div>'; //fin view div
                echo '</a>';
                echo '</div>';//fin content box
                echo '<a href="index.php?r=producto/view&id=' . $item['id_producto'] . '"><div class="product-timer btn-sm" fecha="' . DateTime::createFromFormat('Y-m-d H:i:s', $item->fecha_limite)->format('Y/m/d H:i:s') . '" ></div></a>';
                echo '<p><span style="text-align: center;">' . $item['anuncio'] . '</span></p>';
                echo '</div>';//fin del grid de un producto
                if ($count == 5 || $count == 9 || $count == 12) {
                    echo '</div>';//cierre de la grilla de cuatro
                }
                $count++;
            }
        } elseif (empty($resultSet) && $initial) {
            return $this->render('manage', []);
            //echo '<div class="load-info container-fluid" id="no-more-data" style="height: 40px; width: 100%; text-align: center; margin-top: 40px;  display: inline-block;"><div class=" alert alert-info"><p >No hay mas informacion para ser cargada</p></div></div>';
        } elseif ($initial) {
            return $this->render('manage', []);
        }
    }
}