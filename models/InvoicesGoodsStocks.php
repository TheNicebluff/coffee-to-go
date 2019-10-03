<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoices_goods_stocks".
 *
 * @property int $id
 * @property int $invoices_stocks_id
 * @property int $goods_id
 * @property int $count
 * @property string $price_in
 *
 * @property InvoicesStocks $invoicesStocks
 * @property Goods $goods
 */
class InvoicesGoodsStocks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoices_goods_stocks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoices_stocks_id', 'goods_id', 'count'], 'integer'],
            [['invoices_stocks_id', 'goods_id', 'count', 'price_in'], 'required'],
            [['price_in'], 'number'],
            [['invoices_stocks_id'], 'exist', 'skipOnError' => true, 'targetClass' => InvoicesStocks::className(), 'targetAttribute' => ['invoices_stocks_id' => 'id']],
            [['goods_id'], 'exist', 'skipOnError' => true, 'targetClass' => Goods::className(), 'targetAttribute' => ['goods_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invoices_stocks_id' => 'Инвойс',
            'goods_id' => 'Продукты',
            'count' => 'Кол-во',
            'price_in' => 'Цена',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoicesStocks()
    {
        return $this->hasOne(InvoicesStocks::className(), ['id' => 'invoices_stocks_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'goods_id']);
    }
}
