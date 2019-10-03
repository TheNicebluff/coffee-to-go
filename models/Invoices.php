<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoices".
 *
 * @property int $id
 * @property int $users_id
 * @property int $places_id
 * @property int $stocks_id
 * @property string $date
 * @property string $date_add
 * @property string $name
 *
 * @property Users $users
 * @property Places $places
 * @property Stocks $stocks
 * @property InvoicesGoods $invoicesGoods
 */
class Invoices extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoices';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['users_id', 'places_id', 'stocks_id'], 'integer'],
            [['date', 'date_add'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['users_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['users_id' => 'id']],
            [['places_id'], 'exist', 'skipOnError' => true, 'targetClass' => Places::className(), 'targetAttribute' => ['places_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'users_id' => 'Сотрудник',
            'places_id' => 'Заведение',
            'name' => 'Название / Номер',
            'date' => 'Накладная от',
            'date_add' => 'Дата добавления',
            'stocks_id' => 'Склад',
        ];
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
    public function getPlaces()
    {
        return $this->hasOne(Places::className(), ['id' => 'places_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoicesGoods()
    {
        return $this->hasMany(InvoicesGoods::className(), ['invoices_id' => 'id'])->with('goods');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStocks()
    {
        return $this->hasOne(Stocks::className(), ['id' => 'stocks_id']);
    }

}
