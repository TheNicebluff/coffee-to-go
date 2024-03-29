<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrdersGoods;

/**
 * OrdersItemSearch represents the model behind the search form of `app\models\OrdersItem`.
 */
class OrdersGoods_Search extends OrdersGoods
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'orders_id', 'goods_id', 'products_id', 'count', 'price_out'], 'integer'],
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
        $query = OrdersGoods::find();

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
            'orders_id' => $this->orders_id,
            'goods_id' => $this->goods_id,
            'count' => $this->count,
            'price_out' => $this->price_out,
        ]);

        return $dataProvider;
    }
}
