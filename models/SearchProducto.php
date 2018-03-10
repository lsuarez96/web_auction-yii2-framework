<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Producto;

/**
 * SearchProducto represents the model behind the search form about `app\models\Producto`.
 */
class SearchProducto extends Producto
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_producto', 'tipo'], 'integer'],
            [['anuncio', 'descripcion', 'fecha_limite','sub_tipo', 'id_usuario'], 'safe'],
            [['precio','subasta'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Producto::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith('subTipo');
        $query->joinWith('subasta');
        $query->joinWith('idUsuario');
        // grid filtering conditions
        $query->andFilterWhere([
            'id_producto' => $this->id_producto,
            //'id_usuario' => $this->id_usuario,
           // 'precio' => $this->precio,
           // 'sub_tipo' => $this->sub_tipo,
            'tipo' => $this->tipo,
            'fecha_limite' => $this->fecha_limite,
        ]);

        $query->andFilterWhere(['like', 'anuncio', $this->anuncio])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
        ->andFilterWhere(['<','subasta.precio_actual',$this->precio])
        ->andFilterWhere(['<','subasta.actividad',$this->subasta])
        ->andFilterWhere(['like','usuario.nom_user',$this->id_usuario]);
        if($this->sub_tipo!=null) {
            $query->andFilterWhere(['Like', 'sub_tipo.sub_tipo', $this->sub_tipo]);
        }
        return $dataProvider;
    }
}
