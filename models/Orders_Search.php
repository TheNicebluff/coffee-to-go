<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Orders;

/**
 * Orders_Search represents the model behind the search form of `app\models\Orders`.
 */
class Orders_Search extends Orders
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'users_id', 'places_id', 'total'], 'integer'],
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
        $query = Orders::find();
        $this->load($params);

        // add conditions that should always apply here
        $pageSize = 20;
        if(!empty($this->date_add)){
            $pageSize = 9999;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pageSize' => $pageSize ]
        ]);


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
            'total' => $this->total,
        ]);

        $query->andFilterWhere(['like', 'date_add', $this->date_add]);

        return $dataProvider;
    }
}
