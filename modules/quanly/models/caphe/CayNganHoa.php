<?php

namespace app\modules\quanly\models\caphe;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "4326_cay_nganhoa".
 *
 * @property int $id
 * @property string|null $geom
 * @property string|null $layer
 * @property string|null $lat
 * @property string|null $long
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class CayNganHoa extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '4326_cay_nganhoa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'lat', 'long'], 'string'],
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
            'lat' => 'Lat',
            'long' => 'Long',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
}
