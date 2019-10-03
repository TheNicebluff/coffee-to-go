<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $Places \app\models\Places */

$this->title = 'Радектирование: ' . trim($model->last_name . ' ' . $model->first_name);
$this->params['breadcrumbs'][] = ['label' => 'Сотрудники', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => trim($model->last_name . ' ' . $model->first_name), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Радектирование';
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
        ]) ?>
    </div>
</div>