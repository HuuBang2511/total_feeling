<?php

namespace app\modules\quanly\models\caphe;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "4326_cay_caphe".
 *
 * @property int $id
 * @property string|null $geom
 * @property string|null $layer
 * @property int|null $status
 * @property string|null $lat
 * @property string|null $long
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $geojson
 */
class CayCaPhe extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '4326_cay_caphe';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'lat', 'long', 'geojson'], 'string'],
            [['status', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['layer'], 'string', 'max' => 254],
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
            'geom' => 'Geom',
            'layer' => 'Layer',
            'status' => 'Status',
            'lat' => 'Lat',
            'long' => 'Long',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'geojson' => 'Geojson',
        ];
    }
}
