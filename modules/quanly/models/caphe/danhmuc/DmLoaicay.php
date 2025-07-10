<?php

namespace app\modules\quanly\models\caphe\danhmuc;
use app\modules\quanly\base\QuanlyBaseModel;

use Yii;

/**
 * This is the model class for table "dm_loaicay".
 *
 * @property int $id
 * @property string|null $ten
 * @property string|null $layer_geoserver
 * @property string|null $layer_name
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $updated_by
 * @property int|null $created_by
 *
 * @property Cay[] $cays
 */
class DmLoaicay extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dm_loaicay';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ten', 'layer_geoserver', 'layer_name'], 'string'],
            [['status', 'updated_by', 'created_by'], 'default', 'value' => null],
            [['status', 'updated_by', 'created_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ten' => 'Ten',
            'layer_geoserver' => 'Layer Geoserver',
            'layer_name' => 'Layer Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_by' => 'Created By',
        ];
    }

    /**
     * Gets query for [[Cays]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCays()
    {
        return $this->hasMany(Cay::className(), ['loaicay_id' => 'id']);
    }
}
