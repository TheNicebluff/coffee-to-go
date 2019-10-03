<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoices_stocks".
 *
 * @property int $id
 * @property int $users_id
 * @property int $stocks_id
 * @property string $name
 * @property string $date
 * @property string $date_add
 *
 * @property InvoicesGoodsStocks[] $invoicesGoodsStocks
 * @property Users $users
 * @property Stocks $stocks
 */
class InvoicesStocks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoices_stocks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['users_id', 'stocks_id'], 'integer'],
            [['date', 'date_add', 'name'], 'safe'],
            [['users_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['users_id' => 'id']],
            [['stocks_id'], 'exist', 'skipOnError' => true, 'targetClass' => Stocks::className(), 'targetAttribute' => ['stocks_id' => 'id']],
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
            'users_id' => 'Сотрудник',
            'stocks_id' => 'Склад',
            'date' => 'Дата инвойса',
            'date_add' => 'Дата добавления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoicesGoodsStocks()
    {
        return $this->hasMany(InvoicesGoodsStocks::className(), ['invoices_stocks_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'users_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStocks()
    {
        return $this->hasOne(Stocks::className(), ['id' => 'stocks_id']);
    }
}
