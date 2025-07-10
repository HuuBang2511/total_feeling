<?php

namespace app\modules\quanly\models\caphe;
use app\modules\quanly\base\QuanlyBaseModel;

use Yii;

/**
 * This is the model class for table "vuon".
 *
 * @property int $id
 * @property string|null $maso
 * @property string|null $ngay
 * @property string|null $ten
 * @property float|null $dientich
 * @property string|null $dacdiem
 * @property string|null $diachi
 * @property string|null $sonha
 * @property string|null $tenduong
 * @property string|null $thon
 * @property string|null $phuongxa
 * @property string|null $tinhthanh
 * @property string|null $geom
 * @property string|null $geojson
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $ghichu
 */
class Vuon extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vuon';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['maso', 'ten', 'dacdiem', 'diachi', 'sonha', 'tenduong', 'thon', 'phuongxa', 'tinhthanh', 'geom', 'geojson', 'ghichu'], 'string'],
            [['ngay', 'created_at', 'updated_at'], 'safe'],
            [['dientich'], 'number'],
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
            'ten' => 'Tên vườn',
            'dientich' => 'Diện tích',
            'dacdiem' => 'Đặc điểm',
            'diachi' => 'Địa chỉ',
            'sonha' => 'Số nhà',
            'tenduong' => 'Tên đường',
            'thon' => 'Thôn',
            'phuongxa' => 'Phường xã',
            'tinhthanh' => 'Tỉnh thành',
            'geom' => 'Geom',
            'geojson' => 'Geojson',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'ghichu' => 'Ghi chú',
        ];
    }
}
