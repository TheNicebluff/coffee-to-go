<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ChargeOffGoods */
/* @var $Users array */
/* @var $Goods array */
/* @var $Places array */

$this->title = 'Добавление';
$this->params['breadcrumbs'][] = ['label' => 'Списаные продукты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="row">
        <div class="col-xs-12">
            <div class="">
                <h1 class="h2 pull-left"><?= Html::encode($this->title) ?></h1>
                <span class="pull-right">
                <?= Html::a(Html::tag('i', '', ['class' => 'fas fa-plus']), ['create'], ['class' => 'btn btn-sm btn-success']) ?>
            </span>
            </div>
        </div>
    </div>

<?= $this->render('_form', [
    'model' => $model,
    'Users' => $Users,
    'Goods' => $Goods,
    'Places' => $Places,
]) ?>