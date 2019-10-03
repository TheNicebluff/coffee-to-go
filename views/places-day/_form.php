<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\PlacesDay */
/* @var $form yii\widgets\ActiveForm */
/* @var $Users array */

$UsersArray = [];
foreach ($Users as $user){
    $UsersArray[$user['id']] = $user['first_name'] . ' ' . $user['last_name'];
}
?>

<?php $form = ActiveForm::begin([ 'options' => [  'class' => 'row' ]]); ?>

<?php if (Yii::$app->user->identity->role == 'admin') { ?>
    <div class="col-xs-12 col-lg-6">
        <?= $form->field($model, 'users_id')->widget(Select2::classname(), [
            'data' => $UsersArray,
            'pluginEvents' => [
                "select2:select" => "function(e) {
                    var data = e.params.data;
                    var thisID = data.id;
                }",
            ]
        ]); ?>
    </div>
<?php } ?>
<div class="col-xs-12 col-lg-6">
    <?= $form->field($model, 'date')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => ''],
        'removeButton' => false,
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true
        ]
    ]); ?>
</div>

<div class="col-xs-12 col-lg-6">
    <?= $form->field($model, 'type')->dropDownList(['open' => 'Открытие дня', 'close' => 'Закрытие дня'], ['prompt' => '']) ?>
</div>

<div class="col-xs-12 col-lg-6">
    <?= $form->field($model, 'cash')->textInput(['type' => 'number', 'step' => '0.01']); ?>
</div>

<div class="col-xs-12 col-lg-6">
    <?= $form->field($model, 'coffee_counter')->textInput(['type' => 'number']) ?>
</div>

<div class="col-xs-12">
    <div class="form-group">
        <?= Html::submitButton((($model->isNewRecord) ? 'Добавить' : 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
