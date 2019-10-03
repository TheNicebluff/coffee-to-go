<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model app\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Восстановление пароля';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-lostpass form-box">

    <h1 class="h4"><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

    <?= $form->field($model, 'email', ["template" => "{input}\n{error}"])->textInput(['autofocus' => true, "placeholder" => "Email"]); ?>

    <p><?= Html::submitButton('Восстановить', ['class' => 'btn btn-lg btn-primary btn-block']) ?></p>

    <div class="text-left">
        <small><u><?= Html::a('Войти', ['login'], ['class' => '']) ?></u></small>
    </div>

    <?php ActiveForm::end(); ?>
</div>
