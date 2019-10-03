<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoices_goods".
 *
 * @property int $id
 * @property int $invoices_id
 * @property int $goods_id
 * @property int $count
 * @property int $price_in
 *
 * @property Invoices $invoices
 * @property Goods $goods
 */
class InvoicesGoods extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoices_goods';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoices_id', 'goods_id', 'count'], 'integer'],
            [['invoices_id', 'goods_id', 'count', 'price_in'], 'required'],
            [['price_in'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invoices_id' => 'Инвойс',
            'goods_id' => 'Продукт',
            'count' => 'Кол-во',
            'price_in' => 'Цена',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoices()
    {
        return $this->hasOne(Invoices::className(), ['id' => 'invoices_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'goods_id']);
    }
}
