<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;


/**
 * This is the model class for table "producto".
 *
 * @property integer $id_producto
 * @property integer $id_usuario
 * @property string $anuncio
 * @property string $descripcion
 * @property double $precio
 * @property integer $sub_tipo
 * @property integer $tipo
 * @property string $fecha_limite
 *
 * @property Comentario[] $comentarios
 * @property Foto[] $fotos
 * @property SubTipo $subTipo
 * @property Usuario $idUsuario
 * @property Subasta $subasta
 */
class Producto extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'producto';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['id_usuario', 'anuncio', 'precio', 'sub_tipo', 'tipo', 'fecha_limite'], 'required'],
            [['anuncio'],'required','message' => 'Debe introducir un anuncio para su producto'],
            [['precio'],'required','message' =>'Por favor introduzca el precio inicial de su producto'],
            [['sub_tipo'],'required','message' =>'Por favor elija la sub-categoria de su producto'],
            [['tipo'],'required','message' =>'Por favor elija la categoria de su producto'],
            [['fecha_limite'],'required','message' =>'Por favor especifique cuando debe terminar la subasta'],
            [['id_usuario', 'sub_tipo', 'tipo'], 'integer'],
            [['precio'], 'number',],
            [['fecha_limite'], 'safe'],
            [['anuncio'],'string','max'=>255,'message'=>'Anuncio muy largo,solo se permiten 255 caracteres!!!!'],
            [['descripcion'], 'string', 'max' => 1000,'message'=>'Descripcion muy larga, solo 1000 caracteres!!!!'],
            [['sub_tipo', 'tipo'], 'exist', 'skipOnError' => true, 'targetClass' => Subtipo::className(), 'targetAttribute' => ['sub_tipo' => 'id_sub_tipo', 'tipo' => 'id_tipo']],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['id_usuario' => 'id_usuario']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_producto' => 'Id Producto',
            'id_usuario' => 'Usuario',
            'anuncio' => 'Anuncio',
            'descripcion' => 'Descripcion',
            'precio' => 'Precio',
            'sub_tipo' => 'Sub-Categoria',
            'tipo' => 'Categoria',
            'fecha_limite' => 'Final',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComentarios()
    {
        return $this->hasMany(Comentario::className(), ['id_producto' => 'id_producto']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFotos()
    {
        return $this->hasMany(Foto::className(), ['producto' => 'id_producto']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubTipo()
    {
        return $this->hasOne(Subtipo::className(), ['id_sub_tipo' => 'sub_tipo', 'id_tipo' => 'tipo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUsuario()
    {
        return $this->hasOne(Usuario::className(), ['id_usuario' => 'id_usuario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipo(){
        return $this->hasOne(Tipo::className(),['id_tipo'=>'tipo']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubasta()
    {
        return $this->hasOne(Subasta::className(), ['id_producto' => 'id_producto']);
    }

    public function getSubastaSeguida()
    {
        return $this->hasMany(SubastaSeguida::className(), ['producto' => 'id_producto']);
    }

    public function saveProduct($images)
    {
        $isNewRecord = $this->isNewRecord;
        $record = new Producto();
        $record->anuncio = $this->anuncio;
        $record->fecha_limite = $this->fecha_limite;
        $record->precio = $this->precio;
        $record->tipo = $this->tipo;
        $record->sub_tipo = $this->sub_tipo;
        $record->descripcion = $this->descripcion;
        $record->id_usuario = \Yii::$app->user->id;
        $prod = $record->id_producto;
        $answ = false;

        //  $subastaModel=Subasta::findOne(['id_producto'=>$prod]);
        if ($isNewRecord&&$record->save(false)) {
            $answ=true;
//            $subastaModel = new Subasta();
//            $subastaModel->id_producto = $record->id_producto;
           // $subastaModel->id_usuario = $this->id_usuario;
//            $subastaModel->precio_actual = $this->precio;
//            $subastaModel->terminada = false;
//            $subastaModel->actividad = 0;
//            $subastaModel->save(false);
            foreach ($images->file as $image) {
                $image_record = new Foto();
                $image_record->producto = $record->id_producto;
                $image_record->url = 'images/' . rand(0, 9999) . $image;
                $image_record->descripcion = $record->descripcion;
                $image->saveAs($image_record->url);
                $image_record->save();
            }
        }



        return $answ;
    }

    public static function findLastProducts()
    {
        return static::findBySql("SELECT producto.* FROM producto JOIN subasta ON producto.id_producto=subasta.id_producto WHERE subasta.terminada=false AND producto.fecha_limite>now() ORDER BY id_producto DESC limit 8")->all();
    }

    public static function findProductsFollowedByUser($id_user)
    {
        return static::findBySql("SELECT producto.* FROM producto JOIN subasta ON producto.id_producto=subasta.id_producto JOIN subasta_seguida ON subasta_seguida.subasta_id=subasta.id_subasta WHERE subasta.terminada=false AND producto.fecha_limite>now() AND subasta_seguida.usuario=" . $id_user . " ORDER BY id_producto DESC")->all();

    }

    public static function findUserProducts($id_user)
    {
        return static::findBySql("SELECT producto.* FROM producto join subasta on subasta.id_producto=producto.id_producto WHERE producto.id_usuario=" . $id_user . " AND subasta.terminada=false and (fecha_limite>now() or subasta.actividad>0) ORDER BY fecha_limite ASC")->all();
    }

    public static function findProductsWithMoreActivity(){
        return static::findBySql("Select producto.* from producto join subasta on producto.id_producto=subasta.id_producto WHERE subasta.terminada=false AND producto.fecha_limite>now() ORDER BY subasta.actividad DESC limit 6")->all();

    }

}
