<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Expenses_Search represents the model behind the search form of `app\models\Expenses`.
 */
class Expenses_Search extends Expenses
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'places_id', 'users_id', 'expenses_type_id', 'price'], 'integer'],
            [['name', 'date_add', 'date'], 'safe'],
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
        $query = Expenses::find()->with('users')->with('places')->with('expensesType');

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
            'places_id' => $this->places_id,
            'users_id' => $this->users_id,
            'expenses_type_id' => $this->expenses_type_id,
            'date' => $this->date,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'price', $this->price])
            ->andFilterWhere(['like', 'date_add', $this->date_add]);

        return $dataProvider;
    }
}
