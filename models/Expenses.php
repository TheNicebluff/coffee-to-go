<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "expenses".
 *
 * @property int $id
 * @property int $places_id
 * @property int $users_id
 * @property int $expenses_type_id
 * @property string $name
 * @property int $price
 * @property string $date_add
 * @property string $date
 *
 * @property Places $places
 * @property Users $users
 * @property ExpensesType $expensesType
 */
class Expenses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expenses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['places_id', 'users_id', 'expenses_type_id'], 'integer'],
            [['date_add', 'date'], 'safe'],
            [['price'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['places_id'], 'exist', 'skipOnError' => true, 'targetClass' => Places::className(), 'targetAttribute' => ['places_id' => 'id']],
            [['users_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['users_id' => 'id']],
            [['expenses_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExpensesType::className(), 'targetAttribute' => ['expenses_type_id' => 'id']],
            [['name', 'places_id', 'expenses_type_id', 'price', 'date'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'places_id' => 'Заведение',
            'users_id' => 'Сотрудник',
            'expenses_type_id' => 'Тип',
            'name' => 'Название',
            'price' => 'Сумма',
            'date_add' => 'Дата добавления',
            'date' => 'Дата',
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->date_add = date('Y-m-d H:i:s');
        }

        $this->users_id = Yii::$app->user->identity->id;

        return true;
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
    public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'users_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpensesType()
    {
        return $this->hasOne(ExpensesType::className(), ['id' => 'expenses_type_id']);
    }
}
