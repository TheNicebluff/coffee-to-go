<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $this yii\web\View */
/* @var $model app\models\Categories */
/* @var $CategoriesPlaces app\models\CategoriesPlaces */
/* @var $Places array */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$attributes = [
    'id',
    'name',
    'date_add',
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
    <div class="col-xs-12 col-sm-6">
        <?= DetailView::widget([
            'model' => $model,
            'options' => ['class' => 'table table-striped table_list'],
            'attributes' => $attributes,
        ]); ?>
    </div>
    <div class="col-xs-12 col-sm-6">

        <table class="table table-hover table_list">
            <thead class="">
            <tr>
                <th width="60">ID</th>
                <th>Заведения</th>
                <th width="60"></th>
            </tr>
            </thead>
            <?php if ($model->categoriesPlaces) { ?>
                <tbody>
                <?php foreach ($model->categoriesPlaces as $item) { ?>
                    <tr>
                        <td><?= $item->id; ?></td>
                        <td><?= Html::a($item->places->name, ['view-places', 'id' => $item->id]); ?></td>
                        <td>
                            <?= Html::a(
                                Html::tag('span', '', ['class' => "far fa-trash-alt"]),
                                ['delete-places', 'id' => $item->id],
                                [
                                    'class' => 'btn_action btn btn-sm btn-danger',
                                    'data-pjax' => 0,
                                    'data-method' => 'post',
                                    'data-confirm' => 'Вы уверены, что хотите удалить?'
                                ]); ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            <?php } ?>
        </table>

        <?php $form = ActiveForm::begin(); ?>
        <table class="table ">
            <tbody>
            <tr class="">
                <th width="50%">
                    <?= $form->field($CategoriesPlaces, 'places_id')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map($Places, 'id', 'name'),
                        'pluginEvents' => [
                            "select2:select" => "function(e) {
                                var data = e.params.data;
                                var thisID = data.id;
                            }"]
                    ]); ?>
                    <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?></th>
            </tr>
            </tbody>
        </table>
        <?php ActiveForm::end(); ?>
    </div>
</div>