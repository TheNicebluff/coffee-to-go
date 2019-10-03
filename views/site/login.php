<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form- form-box">

    <h1 class="h4"><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

    <?= $form->field($model, 'email', ["template" => "{input}\n{error}"])->textInput(['autofocus' => true, "placeholder" => "Email"]) ?>

    <?= $form->field($model, 'password', ["template" => "{input}\n{error}"])->passwordInput(["placeholder" => "Пароль"]) ?>

    <div class="text-left">
        <?= $form->field($model, 'rememberMe')->checkbox(['template' => "{input} {label}"])->label("Запомнить меня") ?>
    </div>

    <p><?= Html::submitButton('Войти', ['class' => 'btn btn-lg btn-primary btn-block']) ?></p>

    <div class="mb-3 text-left">
        <small><u><?= Html::a('Восстановить пароль', ['request-password-reset'], ['class' => '']) ?></u></small>
    </div>

    <?php ActiveForm::end(); ?>
</div>
