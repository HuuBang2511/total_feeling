<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;

?>

<div class="khuvuc-form">

    <?php $form = ActiveForm::begin(); ?>

    <h4>Xóa  <?= $model->ten ?></h4>

    <?php ActiveForm::end(); ?>

</div>

