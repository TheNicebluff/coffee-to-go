<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Invoices */
/* @var $Places \app\models\Places */
/* @var $Stocks \app\models\Stocks */

$this->title = 'Добавление';
$this->params['breadcrumbs'][] = ['label' => 'Инвойсы', 'url' => ['index']];
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
    <div class="col-xs-12">
        <?= $this->render('_form', [
            'model' => $model,
            'Places' => $Places,
            'Stocks' => $Stocks,
        ]) ?>
    </div>
</div>