<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Log */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$attributes = [
    'id',
    'users_id',
    'date',
    'type',
    'post:ntext',
    ];
?>
<div class="row">
    <div class="col-xs-12">
        <div class="">
            <h1 class="h2 pull-left"><?= Html::encode($this->title) ?></h1>
            <span class="pull-right">
                <?= Html::a(Html::tag('span', '', ['class' => "far fa-edit"]), ['update', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary']) ?>
                <?= Html::a(Html::tag('span', '', ['class' => "far fa-trash-alt"]), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-sm btn-danger',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить?',
                        'method' => 'post',
                    ],
                ]) ?>
            </span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <?= DetailView::widget([
            'model' => $model,
            'options' => ['class' => 'table table-striped table_list'],
            'attributes' => $attributes,
        ]) ?>
    </div>
</div>