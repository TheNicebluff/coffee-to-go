<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders_products".
 *
 * @property int $id
 * @property int $orders_id
 * @property int $products_id
 * @property int $count
 * @property int $price_out
 * @property string $charge_off
 *
 * @property Orders $orders
 * @property Products $products
 */
class OrdersProducts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders_products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['orders_id', 'products_id', 'count'], 'integer'],
            [['orders_id', 'products_id', 'count', 'price_out'], 'required'],
            [['price_out'], 'number'],
            [['charge_off'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orders_id' => 'Заказ',
            'products_id' => 'Товар',
            'count' => 'Кол-во',
            'price_out' => 'Цена продажи',
            'charge_off' => 'Спсанный товар',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasOne(Orders::className(), ['id' => 'orders_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasOne(Products::className(), ['id' => 'products_id']);
    }
}
