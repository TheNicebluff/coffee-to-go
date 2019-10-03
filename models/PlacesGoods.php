<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "places_goods".
 *
 * @property int $id
 * @property int $places_id
 * @property int $goods_id
 * @property int $count
 *
 * @property Goods[] $goods
 * @property Places[] $places
 */
class PlacesGoods extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'places_goods';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['places_id', 'goods_id', 'count'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'places_id' => 'Заведение',
            'goods_id' => 'Продукт',
            'count' => 'Кол-во',
        ];
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
