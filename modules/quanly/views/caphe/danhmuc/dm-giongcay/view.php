<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\caphe\danhmuc\DmGiongcay */
?>
<div class="dm-giongcay-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'ten',
            [
                'label' => 'Loại cây',
                'value' => function($model){
                    return ($model->loaicay_id != null) ? $model->loaicay->ten : '';
                }
            ],
        ],
    ]) ?>

</div>
