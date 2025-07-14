<?php

namespace app\modules\quanly\models\caphe;
use app\modules\quanly\base\QuanlyBaseModel;

use Yii;

/**
 * This is the model class for table "cay_duaxiemlun_datrong".
 *
 * @property int $id
 * @property string|null $macay
 * @property string|null $thongtincay
 * @property string|null $lat
 * @property string|null $long
 * @property string|null $geom
 * @property string|null $geojson
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class CayDuaXiemLunDaTrong extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cay_duaxiemlun_datrong';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['macay', 'thongtincay', 'lat', 'long', 'geom', 'geojson'], 'string'],
            [['status', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['lat', 'long'], 'required']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'macay' => 'Mã cây',
            'thongtincay' => 'Thông tin cây',
            'lat' => 'Lat',
            'long' => 'Long',
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
