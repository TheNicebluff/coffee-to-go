<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property string $date_add
 * @property int $users_id
 * @property int $places_id
 * @property int $total
 *
 * @property Users $users
 * @property Places $places
 * @property OrdersGoods $ordersGoods
 * @property OrdersProducts $ordersProducts
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_add'], 'safe'],
            [['users_id', 'places_id', 'total'], 'integer'],
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
            'date_add' => 'Дата добавления',
            'users_id' => 'Сотрудник',
            'places_id' => 'Заведение',
            'total' => 'Сумма заказа',
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
    public function getOrdersGoods()
    {
        return $this->hasMany(OrdersGoods::className(), ['orders_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdersProducts()
    {
        return $this->hasMany(OrdersProducts::className(), ['orders_id' => 'id']);
    }
}
