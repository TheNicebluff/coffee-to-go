<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Invoices;

/**
 * Invoices_Search represents the model behind the search form of `app\models\Invoices`.
 */
class Invoices_Search extends Invoices
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'users_id', 'places_id', 'stocks_id'], 'integer'],
            [['date', 'date_add'], 'safe'],
            [['name'], 'string'],
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
        $query = Invoices::find();
        $this->load($params);
        $pageSize = 20;
        if(!empty($this->date)){
            $pageSize = 9999;
        }

        // add conditions that should always apply here

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
            'date' => $this->date,
            'stocks_id' => $this->stocks_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'date_add', $this->date_add]);


        return $dataProvider;
    }
}
