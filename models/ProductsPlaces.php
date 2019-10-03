<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_places".
 *
 * @property int $id
 * @property int $places_id
 * @property int $products_id
 *
 * @property Places $places
 * @property Products $product
 */
class ProductsPlaces extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products_places';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['places_id', 'products_id'], 'integer'],
            [['places_id'], 'exist', 'skipOnError' => true, 'targetClass' => Places::className(), 'targetAttribute' => ['places_id' => 'id']],
            [['products_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['products_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'places_id' => 'Places ID',
            'products_id' => 'Product ID',
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
    public function getProducts()
    {
        return $this->hasOne(Products::className(), ['id' => 'products_id']);
    }
}
