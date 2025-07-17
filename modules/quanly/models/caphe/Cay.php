<?php

namespace app\modules\quanly\models\caphe;
use app\modules\quanly\base\QuanlyBaseModel;
use app\modules\quanly\models\caphe\danhmuc\DmLoaicay;
use app\modules\quanly\models\caphe\danhmuc\DmNhomcay;
use app\modules\quanly\models\caphe\danhmuc\DmGiongcay;
use app\modules\quanly\models\caphe\Vuon;
use app\modules\quanly\models\caphe\Khuvuc;
use Yii;

/**
 * This is the model class for table "cay".
 *
 * @property int $id
 * @property string|null $maso
 * @property string|null $ngay
 * @property int|null $nhomcay_id
 * @property int|null $loaicay_id
 * @property string|null $giong
 * @property float|null $chieucao
 * @property float|null $duongkinhthan
 * @property float|null $duongkinhtan
 * @property string|null $loaire
 * @property string|null $khanang_giudat
 * @property string|null $ghichu_sinhkhoi
 * @property string|null $dacdiem
 * @property string|null $nguongoc
 * @property string|null $ghichu
 * @property string|null $geom
 * @property string|null $geojson
 * @property string|null $lat
 * @property string|null $long
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $vuon_id
 * @property int|null $khuvuc_id
 * @property int|null $giongcay_id
 * @property int|null $loaitrong
 *
 * @property DmGiongcay $giongcay
 * @property DmLoaicay $loaicay
 * @property DmNhomcay $nhomcay
 * @property Khuvuc $khuvuc
 * @property Vuon $vuon
 */
class Cay extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cay';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['maso', 'giong', 'loaire', 'khanang_giudat', 'ghichu_sinhkhoi', 'dacdiem', 'nguongoc', 'ghichu', 'geom', 'geojson', 'lat', 'long'], 'string'],
            [['ngay', 'created_at', 'updated_at'], 'safe'],
            [['nhomcay_id', 'loaicay_id', 'status', 'created_by', 'updated_by', 'vuon_id', 'khuvuc_id', 'giongcay_id', 'loaitrong'], 'default', 'value' => null],
            [['nhomcay_id', 'loaicay_id', 'status', 'created_by', 'updated_by', 'vuon_id', 'khuvuc_id', 'giongcay_id', 'loaitrong'], 'integer'],
            [['chieucao', 'duongkinhthan', 'duongkinhtan'], 'number'],
            [['giongcay_id'], 'exist', 'skipOnError' => true, 'targetClass' => DmGiongcay::className(), 'targetAttribute' => ['giongcay_id' => 'id']],
            [['loaicay_id'], 'exist', 'skipOnError' => true, 'targetClass' => DmLoaicay::className(), 'targetAttribute' => ['loaicay_id' => 'id']],
            [['nhomcay_id'], 'exist', 'skipOnError' => true, 'targetClass' => DmNhomcay::className(), 'targetAttribute' => ['nhomcay_id' => 'id']],
            [['khuvuc_id'], 'exist', 'skipOnError' => true, 'targetClass' => Khuvuc::className(), 'targetAttribute' => ['khuvuc_id' => 'id']],
            [['vuon_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vuon::className(), 'targetAttribute' => ['vuon_id' => 'id']],
            [['lat', 'long'], 'required'],
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
            'nhomcay_id' => 'Nhóm cây',
            'loaicay_id' => 'Loại cây',
            'giong' => 'Giống',
            'chieucao' => 'Chiều cao',
            'duongkinhthan' => 'Đường kính thân',
            'duongkinhtan' => 'Đường kính tán',
            'loaire' => 'Loại rể',
            'khanang_giudat' => 'Khả năng giữ đất',
            'ghichu_sinhkhoi' => 'Ghi chú sinh khối',
            'dacdiem' => 'Đặc điểm',
            'nguongoc' => 'Nguồn gốc',
            'ghichu' => 'Ghi chú',
            'geom' => 'Geom',
            'geojson' => 'Geojson',
            'lat' => 'Lat',
            'long' => 'Long',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'vuon_id' => 'Luống',
            'khuvuc_id' => 'Phân khu',
            'giongcay_id' => 'Giống cây',
            'loaitrong' => 'Loại trồng',
        ];
    }

    /**
     * Gets query for [[Giongcay]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGiongcay()
    {
        return $this->hasOne(DmGiongcay::className(), ['id' => 'giongcay_id']);
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

    /**
     * Gets query for [[Nhomcay]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNhomcay()
    {
        return $this->hasOne(DmNhomcay::className(), ['id' => 'nhomcay_id']);
    }

    /**
     * Gets query for [[Khuvuc]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKhuvuc()
    {
        return $this->hasOne(Khuvuc::className(), ['id' => 'khuvuc_id']);
    }

    /**
     * Gets query for [[Vuon]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVuon()
    {
        return $this->hasOne(Vuon::className(), ['id' => 'vuon_id']);
    }
}
