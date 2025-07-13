<?php

namespace app\modules\quanly\models\caphe\danhmuc;
use app\modules\quanly\base\QuanlyBaseModel;
use app\modules\quanly\models\caphe\danhmuc\DmLoaicay;
use Yii;

/**
 * This is the model class for table "dm_giongcay".
 *
 * @property int $id
 * @property string|null $ten
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $loaicay_id
 *
 * @property Cay[] $cays
 * @property DmLoaicay $loaicay
 */
class DmGiongcay extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dm_giongcay';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ten'], 'string'],
            [['status', 'created_by', 'updated_by', 'loaicay_id'], 'default', 'value' => null],
            [['status', 'created_by', 'updated_by', 'loaicay_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['loaicay_id'], 'exist', 'skipOnError' => true, 'targetClass' => DmLoaicay::className(), 'targetAttribute' => ['loaicay_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ten' => 'Tên',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'loaicay_id' => 'Loại cây',
        ];
    }

    /**
     * Gets query for [[Cays]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCays()
    {
        return $this->hasMany(Cay::className(), ['giongcay_id' => 'id']);
    }

    /**
     * Gets query for [[Loaicay]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLoaicay()
    {
        return $this->hasOne(DmLoaicay::className(), ['id' => 'loaicay_id']);
    }
}
