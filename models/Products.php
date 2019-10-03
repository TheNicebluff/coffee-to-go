<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $name
 * @property int $price_out
 * @property string $image
 * @property object $img
 *
 * @property ProductsGoods[] $productsGoods
 * @property ProductsPlaces[] $productsPlaces
 */
class Products extends \yii\db\ActiveRecord
{

    public $img;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price_out'], 'number'],
            [['name'], 'string', 'max' => 255],
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
            'name' => 'Название',
            'price_out' => 'Цена продажи',
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
        return $this->hasMany(ProductsGoods::className(), ['products_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductsPlaces()
    {
        return $this->hasMany(ProductsPlaces::className(), ['products_id' => 'id']);
    }
}
