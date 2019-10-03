<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Goods */
/* @var $form yii\widgets\ActiveForm */

$UnitArr = [];
foreach (Yii::$app->params['unit'] as $key => $item) {
    $UnitArr[$key] = $item['name'];
}
?>

<?php $form = ActiveForm::begin(['options' => ['class' => 'row', 'enctype' => 'multipart/form-data']]); ?>

    <div class="col-xs-12 col-lg-6">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-xs-12 col-lg-6">
        <?= $form->field($model, 'unit')->dropDownList($UnitArr, ['prompt' => '']) ?>
    </div>

    <div class="col-xs-12 col-lg-6">
        <?= $form->field($model, 'type')->dropDownList(Yii::$app->params['goods_type'], ['prompt' => '']) ?>
    </div>

    <div class="col-xs-12 col-lg-6">
        <?= $form->field($model, 'price_out')->textInput(['type' => 'number', 'step' => '0.01']) ?>
    </div>

    <div class="col-xs-12 col-lg-6">
        <?= $form->field($model, 'image')->fileInput() ?>
    </div>

<? /*
    <div class="col-xs-12 col-lg-6">
        <?= $form->field($model, 'price_in')->textInput(['type' => 'number', 'step' => '0.01']) ?>
    </div>
*/ ?>
    <div class="col-xs-12">
        <div class="form-group">
            <?= Html::submitButton((($model->isNewRecord) ? 'Добавить' : 'Сохранить'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>