<?php

namespace app\modules\quanly\models\caphe;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\caphe\Cay;

/**
 * CaySearch represents the model behind the search form about `app\modules\quanly\models\caphe\Cay`.
 */
class CaySearch extends Cay
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'nhomcay_id', 'loaicay_id', 'status', 'created_by', 'updated_by', 'vuon_id', 'khuvuc_id'], 'integer'],
            [['maso', 'ngay', 'giong', 'loaire', 'khanang_giudat', 'ghichu_sinhkhoi', 'dacdiem', 'nguongoc', 'ghichu', 'geom', 'geojson', 'lat', 'long', 'created_at', 'updated_at'], 'safe'],
            [['chieucao', 'duongkinhthan', 'duongkinhtan'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Cay::find()->where(['status' => 1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'ngay' => $this->ngay,
            'nhomcay_id' => $this->nhomcay_id,
            'loaicay_id' => $this->loaicay_id,
            'chieucao' => $this->chieucao,
            'duongkinhthan' => $this->duongkinhthan,
            'duongkinhtan' => $this->duongkinhtan,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'vuon_id' => $this->vuon_id,
            'khuvuc_id' => $this->khuvuc_id,
        ]);

        $query->andFilterWhere(['like', 'upper(maso)', mb_strtoupper($this->maso)])
            ->andFilterWhere(['like', 'upper(giong)', mb_strtoupper($this->giong)])
            ->andFilterWhere(['like', 'upper(loaire)', mb_strtoupper($this->loaire)])
            ->andFilterWhere(['like', 'upper(khanang_giudat)', mb_strtoupper($this->khanang_giudat)])
            ->andFilterWhere(['like', 'upper(ghichu_sinhkhoi)', mb_strtoupper($this->ghichu_sinhkhoi)])
            ->andFilterWhere(['like', 'upper(dacdiem)', mb_strtoupper($this->dacdiem)])
            ->andFilterWhere(['like', 'upper(nguongoc)', mb_strtoupper($this->nguongoc)])
            ->andFilterWhere(['like', 'upper(ghichu)', mb_strtoupper($this->ghichu)])
            ->andFilterWhere(['like', 'upper(geom)', mb_strtoupper($this->geom)])
            ->andFilterWhere(['like', 'upper(geojson)', mb_strtoupper($this->geojson)])
            ->andFilterWhere(['like', 'upper(lat)', mb_strtoupper($this->lat)])
            ->andFilterWhere(['like', 'upper(long)', mb_strtoupper($this->long)]);

        return $dataProvider;
    }

    public function getExportColumns()
    {
        return [
            [
                'class' => 'kartik\grid\SerialColumn',
            ],
            'id',
        'maso',
        'ngay',
        'nhomcay_id',
        'loaicay_id',
        'giong',
        'chieucao',
        'duongkinhthan',
        'duongkinhtan',
        'loaire',
        'khanang_giudat',
        'ghichu_sinhkhoi',
        'dacdiem',
        'nguongoc',
        'ghichu',
        'geom',
        'geojson',
        'lat',
        'long',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',        ];
    }
}
