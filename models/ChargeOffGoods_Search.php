<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ChargeOffGoods;

/**
 * ChargeOffGoods_Search represents the model behind the search form of `app\models\ChargeOffGoods`.
 */
class ChargeOffGoods_Search extends ChargeOffGoods
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'users_id', 'places_id', 'goods_id', 'count'], 'integer'],
            [['date_add'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = ChargeOffGoods::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pageSize' => 20 ]
        ]);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'users_id' => $this->users_id,
            'places_id' => $this->places_id,
            'goods_id' => $this->goods_id,
            'count' => $this->count
        ]);

        $query->andFilterWhere(['like', 'date_add', $this->date_add]);

        return $dataProvider;
    }
}
