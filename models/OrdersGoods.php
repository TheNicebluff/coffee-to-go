<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders_goods".
 *
 * @property int $id
 * @property int $orders_id
 * @property int $goods_id
 * @property int $count
 * @property int $price_out
 *
 * @property Orders $orders
 * @property Goods $goods
 */
class OrdersGoods extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders_goods';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['orders_id', 'goods_id', 'count'], 'integer'],
            [['orders_id', 'goods_id', 'count', 'price_out'], 'required'],
            [['price_out'], 'number'],
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
            'goods_id' => 'Продукты',
            'count' => 'Кол-во',
            'price_out' => 'Цена продажи',
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
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'goods_id']);
    }
}
