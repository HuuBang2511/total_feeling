<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\quanly\models\caphe\Cay */

?>
<div class="cay-create">
    <?= $this->render('_form', [
        'model' => $model,
        'loaicay' => $loaicay,
        'nhomcay' => $nhomcay,
        'vuon' => $vuon,
        'khuvuc' => $khuvuc,
        'giongcay' => $giongcay,
    ]) ?>
</div>
