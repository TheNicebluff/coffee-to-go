<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categories_places_goods".
 *
 * @property int $id
 * @property int $goods_id
 * @property int $categories_places_id
 *
 * @property Goods $goods
 * @property CategoriesPlaces $categoriesPlaces
 * @property PlacesGoods $placesGoods
 */
class CategoriesPlacesGoods extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categories_places_goods';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id', 'categories_places_id'], 'integer'],
            [['goods_id'], 'exist', 'skipOnError' => true, 'targetClass' => Goods::className(), 'targetAttribute' => ['goods_id' => 'id']],
            [['categories_places_id'], 'exist', 'skipOnError' => true, 'targetClass' => CategoriesPlaces::className(), 'targetAttribute' => ['categories_places_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => 'Продукт',
            'categories_places_id' => 'Категория заведения',
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
    public function getCategoriesPlaces()
    {
        return $this->hasOne(CategoriesPlaces::className(), ['id' => 'categories_places_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlacesGoods()
    {
        return $this->hasOne(PlacesGoods::className(), ['goods_id' => 'goods_id']);
    }
}
