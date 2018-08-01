<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\WmsWsBill */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wms-ws-bill-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'ref')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'action_time')->textInput() ?>

    <?= $form->field($model, 'action_user_id')->textInput() ?>

    <?= $form->field($model, 'customer_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_time')->textInput() ?>

    <?= $form->field($model, 'inventory_money')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'warehouse_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
