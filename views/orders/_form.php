<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Orders */
/* @var $form yii\widgets\ActiveForm */
/* @var $Places \app\models\Places */
/* @var $Users \app\models\Users */

?>

<?php $form = ActiveForm::begin(['options' => ['class' => 'row']]); ?>

<div class="col-xs-12">
    <?= $form->field($model, 'places_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($Places, 'id', 'name'),
        'pluginEvents' => [
            "select2:select" => "function(e) {
	            var data = e.params.data;
                var thisID = data.id;
            }",
        ]
    ]); ?>
</div>

<div class="col-xs-12">
    <div class="form-group">
        <?= Html::submitButton((($model->isNewRecord) ? 'Добавить' : 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
