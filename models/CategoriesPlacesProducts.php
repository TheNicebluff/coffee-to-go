<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categories_places_products".
 *
 * @property int $id
 * @property int $products_id
 * @property int $categories_places_id
 *
 * @property Products $products
 * @property CategoriesPlaces $categoriesPaces
 */
class CategoriesPlacesProducts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categories_places_products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['products_id', 'categories_places_id'], 'integer'],
            [['products_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['products_id' => 'id']],
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
            'products_id' => 'Товар',
            'categories_places_id' => 'Категория заведения',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasOne(Products::className(), ['id' => 'products_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriesPaces()
    {
        return $this->hasOne(CategoriesPlaces::className(), ['id' => 'categories_places_id']);
    }
}
