<?php

namespace app\modules\quanly\models\caphe;
use app\modules\quanly\base\QuanlyBaseModel;

use Yii;

/**
 * This is the model class for table "khuvuc".
 *
 * @property int $id
 * @property string|null $maso
 * @property string|null $ngay
 * @property string|null $ten
 * @property string|null $dacdiem
 * @property string|null $ghichu
 * @property string|null $geom
 * @property string|null $geojson
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class Khuvuc extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'khuvuc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['maso', 'ten', 'dacdiem', 'ghichu', 'geom', 'geojson'], 'string'],
            [['ngay', 'created_at', 'updated_at'], 'safe'],
            [['status', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['status', 'created_by', 'updated_by'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'maso' => 'Mã số',
            'ngay' => 'Ngày',
            'ten' => 'Tên phân khú',
            'dacdiem' => 'Đặc điểm',
            'ghichu' => 'Ghi chú',
            'geom' => 'Geom',
            'geojson' => 'Geojson',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
}
