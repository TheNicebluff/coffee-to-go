<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "charge_off_goods".
 *
 * @property int $id
 * @property int $users_id
 * @property int $places_id
 * @property int $goods_id
 * @property int $count
 * @property string $date_add
 *
 * @property Users $users
 * @property Places $places
 * @property Goods $goods
 */
class ChargeOffGoods extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'charge_off_goods';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['users_id', 'places_id', 'goods_id', 'count'], 'integer'],
            [['date_add'], 'safe'],
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
            'goods_id' => 'Продукт',
            'count' => 'Кол-во',
            'date_add' => 'Дата добавления',
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
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'goods_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaces()
    {
        return $this->hasOne(Places::className(), ['id' => 'places_id']);
    }
}
