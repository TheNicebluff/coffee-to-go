<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property string $name
 * @property string $date_add
 *
 * @property CategoriesPlaces[] $categoriesPlaces
 */
class Categories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_add'], 'safe'],
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
            'date_add' => 'Дата добавления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriesPlaces()
    {
        return $this->hasMany(CategoriesPlaces::className(), ['categories_id' => 'id']);
    }
}
