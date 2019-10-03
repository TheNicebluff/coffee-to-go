<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "places".
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $date_open
 * @property string $date_add
 * @property string $phone
 *
 * @property Expenses[] $expenses
 * @property Invoices[] $invoices
 * @property Orders[] $orders
 * @property ProductsPlaces[] $productsPlaces
 * @property PlacesGoods[] $placesGoods
 * @property Users[] $users
 */
class Places extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'places';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_open', 'date_add'], 'safe'],
            [['name', 'address', 'phone'], 'string', 'max' => 255],
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
            'address' => 'Адрес',
            'date_open' => 'Дата открытия',
            'date_add' => 'Дата добавления',
            'phone' => 'Телефон',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpenses()
    {
        return $this->hasMany(Expenses::className(), ['places_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoices()
    {
        return $this->hasMany(Invoices::className(), ['places_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Orders::className(), ['places_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductsPlaces()
    {
        return $this->hasMany(ProductsPlaces::className(), ['places_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlacesGoods()
    {
        return $this->hasMany(PlacesGoods::className(), ['places_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(Users::className(), ['places_id' => 'id']);
    }
}
