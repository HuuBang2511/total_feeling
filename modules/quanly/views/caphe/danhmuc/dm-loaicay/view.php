<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\caphe\danhmuc\DmLoaicay */
?>
<div class="dm-loaicay-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'ten',
            [
                'label' => 'Nhóm cây',
                'value' => function($model){
                    return ($model->nhomcay_id != null) ? $model->nhomcay->ten : '';
                }
            ],
        ],
    ]) ?>

</div>
