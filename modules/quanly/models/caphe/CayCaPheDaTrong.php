<?php

namespace app\modules\quanly\models\caphe;
use app\modules\quanly\base\QuanlyBaseModel;
use app\modules\quanly\models\caphe\danhmuc\DmLoaicaphe;

use Yii;

/**
 * This is the model class for table "cay_caphe_datrong".
 *
 * @property int $id
 * @property string|null $geom
 * @property string|null $macay
 * @property string|null $thongtincay
 * @property string|null $lat
 * @property string|null $long
 * @property string|null $geojson
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $loaicaphe_id
 *
 * @property DmLoaicaphe $loaicaphe
 */
class CayCaPheDaTrong extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cay_caphe_datrong';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'macay', 'thongtincay', 'lat', 'long', 'geojson'], 'string'],
            [['status', 'created_by', 'updated_by', 'loaicaphe_id'], 'default', 'value' => null],
            [['status', 'created_by', 'updated_by', 'loaicaphe_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['loaicaphe_id'], 'exist', 'skipOnError' => true, 'targetClass' => DmLoaicaphe::className(), 'targetAttribute' => ['loaicaphe_id' => 'id']],
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
            'macay' => 'Mã cây',
            'thongtincay' => 'Thông tin cây',
            'lat' => 'Lat',
            'long' => 'Long',
            'geojson' => 'Geojson',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'loaicaphe_id' => 'Loại cà phê',
        ];
    }

    /**
     * Gets query for [[Loaicaphe]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLoaicaphe()
    {
        return $this->hasOne(DmLoaicaphe::className(), ['id' => 'loaicaphe_id']);
    }
}
