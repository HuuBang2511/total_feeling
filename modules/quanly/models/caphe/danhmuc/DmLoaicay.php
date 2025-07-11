<?php

namespace app\modules\quanly\models\caphe\danhmuc;
use app\modules\quanly\base\QuanlyBaseModel;
use app\modules\quanly\models\caphe\danhmuc\DmNhomcay;
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
 * @property int|null $nhomcay_id
 *
 * @property Cay[] $cays
 * @property DmNhomcay $nhomcay
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
            [['status', 'updated_by', 'created_by', 'nhomcay_id'], 'default', 'value' => null],
            [['status', 'updated_by', 'created_by', 'nhomcay_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['nhomcay_id'], 'exist', 'skipOnError' => true, 'targetClass' => DmNhomcay::className(), 'targetAttribute' => ['nhomcay_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ten' => 'tên',
            'layer_geoserver' => 'Layer Geoserver',
            'layer_name' => 'Layer Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_by' => 'Created By',
            'nhomcay_id' => 'Nhóm cây',
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

    /**
     * Gets query for [[Nhomcay]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNhomcay()
    {
        return $this->hasOne(DmNhomcay::className(), ['id' => 'nhomcay_id']);
    }
}
