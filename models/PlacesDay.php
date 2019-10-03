<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "places_day".
 *
 * @property int $id
 * @property int $users_id
 * @property int $places_id
 * @property string $date
 * @property string $date_add
 * @property string $type
 */
class PlacesDay extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'places_day';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['users_id', 'places_id', 'coffee_counter'], 'integer'],
            [['cash'], 'number'],
            [['users_id', 'coffee_counter', 'cash', 'type'], 'required'],
            [['date', 'date_add', 'cash'], 'safe'],
            [['type'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'users_id' => 'Сотрудник',
            'places_id' => 'Заведение',
            'date' => 'Дата',
            'date_add' => 'Дата добавления',
            'type' => 'Тип',
            'cash' => 'Касса',
            'coffee_counter' => 'Счетчик кофе',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'users_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaces()
    {
        return $this->hasOne(Places::className(), ['id' => 'places_id']);
    }
}
