<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
?>

<div class="dm-loaicay-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ten')->textInput() ?>

    <?= $form->field($model, 'loaicay_id')->widget(Select2::className(), [
            'data' => ArrayHelper::map($loaicay, 'id', 'ten'),
            'options' => [
                'id' => 'loaicay_id',
                'prompt' => 'Chọn loại cây'
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
    ]) ?>


  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
