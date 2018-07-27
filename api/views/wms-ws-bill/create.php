<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\WmsWsBill */

$this->title = 'Create Wms Ws Bill';
$this->params['breadcrumbs'][] = ['label' => 'Wms Ws Bills', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wms-ws-bill-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
