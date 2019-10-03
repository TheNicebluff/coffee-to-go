<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stocks_goods".
 *
 * @property int $id
 * @property int $stocks_id
 * @property int $goods_id
 * @property int $count
 * @property string $price_in
 *
 * @property Stocks $stocks
 * @property Goods $goods
 */
class StocksGoods extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stocks_goods';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['stocks_id', 'goods_id', 'count'], 'integer'],
            [['price_in'], 'number'],
            [['stocks_id'], 'exist', 'skipOnError' => true, 'targetClass' => Stocks::className(), 'targetAttribute' => ['stocks_id' => 'id']],
            [['goods_id'], 'exist', 'skipOnError' => true, 'targetClass' => Goods::className(), 'targetAttribute' => ['goods_id' => 'id']]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stocks_id' => 'Склад',
            'goods_id' => 'Продукты',
            'count' => 'Кол-во',
            'price_in' => 'Цена',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStocks()
    {
        return $this->hasOne(Stocks::className(), ['id' => 'stocks_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'goods_id']);
    }
}
