<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categories_places".
 *
 * @property int $id
 * @property int $places_id
 * @property int $categories_id
 *
 * @property Places $places
 * @property Categories $categories
 * @property CategoriesPlacesGoods[] $categoriesPlacesGoods
 * @property CategoriesPlacesProducts[] $categoriesPlacesProducts
 */
class CategoriesPlaces extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categories_places';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['places_id', 'categories_id'], 'integer'],
            [['places_id'], 'exist', 'skipOnError' => true, 'targetClass' => Places::className(), 'targetAttribute' => ['places_id' => 'id']],
            [['categories_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['categories_id' => 'id']],
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
            'categories_id' => 'Категория',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaces()
    {
        return $this->hasOne(Places::className(), ['id' => 'places_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasOne(Categories::className(), ['id' => 'categories_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriesPlacesGoods()
    {
        return $this->hasMany(CategoriesPlacesGoods::className(), ['categories_places_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriesPlacesProducts()
    {
        return $this->hasMany(CategoriesPlacesProducts::className(), ['categories_places_id' => 'id']);
    }
}
