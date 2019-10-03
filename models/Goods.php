<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "goods".
 *
 * @property int $id
 * @property string $name
 * @property int $unit
 * @property int $price_in
 * @property string $type
 * @property int $price_out
 * @property string $image
 * @property object $img
 *
 * @property ProductsGoods[] $productsGoods
 */
class Goods extends \yii\db\ActiveRecord
{
    public $img = '';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['unit'], 'integer'],
            [['price_in', 'price_out'], 'number'],
            [['type'], 'string'],
            [['name'], 'string',  'max' => 255],
            [['image'], 'file', 'extensions' => 'png, jpg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Товар',
            'unit' => 'Ед-ца измерения',
            'price_in' => 'Закупка (Цена)',
            'type' => 'Тип',
            'price_out' => 'Продажа (Цена)',
            'image' => 'Изображение',
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->img->saveAs('uploads/' . $this->img->baseName . '.' . $this->img->extension);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductsGoods()
    {
        return $this->hasMany(ProductsGoods::className(), ['goods_id' => 'id']);
    }
}
