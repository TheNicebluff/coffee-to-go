<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

/* @var $CategoriesPlaces app\models\CategoriesPlaces */

$this->title = 'Главная';
$Currency = Yii::$app->params['currency'];
$Units = Yii::$app->params['unit'];


$hidden = 'hidden';
if (isset($_COOKIE['order_id']) && $_COOKIE['order_id'] > 0) {
    $hidden = '';
}

foreach ($CategoriesPlaces as $item) {
}
?>
<div class="site-index">
    <div class="body-content">
        <div class="row">
            <div class="col-lg-12">

                <h1><?= $this->title; ?></h1>

                <?php foreach ($CategoriesPlaces as $item) {

                    $arr_result = [];
                    $arr_result_name = [];

                    foreach ($item->categoriesPlacesGoods as $value) {
                        $arr_result[] = array(
                            'image' => $value->goods->image,
                            'name' => $value->goods->name,
                            'price_out' => $value->goods->price_out,
                            'count' => $value->placesGoods['count'],
                            'id' => $value->goods_id,
                            'type' => 'goods',
                        );
                        $arr_result_name[] = $value->goods->name;
                    }

                    foreach ($item->categoriesPlacesProducts as $value) {
                        $arr_result[] = array(
                            'image' => $value->products->image,
                            'name' => $value->products->name,
                            'price_out' => $value->products->price_out,
                            'count' => false,
                            'id' => $value->products_id,
                            'type' => 'products',
                        );
                        $arr_result_name[] = $value->products->name;
                    }

                    asort($arr_result_name);
                    array_multisort($arr_result, $arr_result_name);

                    ?>
                    <div class="row">

                        <div class="col-xs-12">
                            <h2><?php echo $item->categories->name; ?></h2>
                        </div>

                        <?php foreach ($arr_result as $value) { ?>
                            <div class="col-xs-6 col-sm-4 col-lg-2">
                                <div class="thumbnail">
                                    <?php
                                    if (!empty($value['image'])) {
                                        echo Html::img('/web/uploads/' . $value['image'], ['width' => '150']);
                                    } ?>
                                    <div class="caption">
                                        <h3><?php echo $value['name']; ?></h3>
                                        <p><b><?php echo $value['price_out']; ?></b> <?php echo $Currency; ?></p>
                                        <?php
                                        if ($value['type'] == 'goods' && $value['count'] > 0) {
                                            echo Html::a('В корзину', ['add-goods-order', 'goods_id' => $value['id']], [
                                                'title' => Yii::t('app', 'В корзину'),
                                                'class' => 'btn btn-sm btn-info ' . $hidden
                                            ]);
                                        } else if ($value['type'] == 'products') {
                                            echo Html::a('В корзину', ['add-products-order', 'products_id' => $value['id']], [
                                                'title' => Yii::t('app', 'В корзину'),
                                                'class' => 'btn btn-sm btn-info ' . $hidden
                                            ]);
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        <? } ?>

                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
