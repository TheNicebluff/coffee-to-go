<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stocks".
 *
 * @property int $id
 * @property string $name
 *
 * @property InvoicesStocks[] $invoicesStocks
 * @property StocksGoods[] $stocksGoods
 */
class Stocks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stocks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoicesStocks()
    {
        return $this->hasMany(InvoicesStocks::className(), ['stocks_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStocksGoods()
    {
        return $this->hasMany(StocksGoods::className(), ['stocks_id' => 'id']);
    }
}
