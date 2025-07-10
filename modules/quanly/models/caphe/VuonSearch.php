<?php

namespace app\modules\quanly\models\caphe;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\caphe\Vuon;

/**
 * VuonSearch represents the model behind the search form about `app\modules\quanly\models\caphe\Vuon`.
 */
class VuonSearch extends Vuon
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['maso', 'ngay', 'ten', 'dacdiem', 'diachi', 'sonha', 'tenduong', 'thon', 'phuongxa', 'tinhthanh', 'geom', 'geojson', 'created_at', 'updated_at', 'ghichu'], 'safe'],
            [['dientich'], 'number'],
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
        $query = Vuon::find();

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
            'dientich' => $this->dientich,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'upper(maso)', mb_strtoupper($this->maso)])
            ->andFilterWhere(['like', 'upper(ten)', mb_strtoupper($this->ten)])
            ->andFilterWhere(['like', 'upper(dacdiem)', mb_strtoupper($this->dacdiem)])
            ->andFilterWhere(['like', 'upper(diachi)', mb_strtoupper($this->diachi)])
            ->andFilterWhere(['like', 'upper(sonha)', mb_strtoupper($this->sonha)])
            ->andFilterWhere(['like', 'upper(tenduong)', mb_strtoupper($this->tenduong)])
            ->andFilterWhere(['like', 'upper(thon)', mb_strtoupper($this->thon)])
            ->andFilterWhere(['like', 'upper(phuongxa)', mb_strtoupper($this->phuongxa)])
            ->andFilterWhere(['like', 'upper(tinhthanh)', mb_strtoupper($this->tinhthanh)])
            ->andFilterWhere(['like', 'upper(geom)', mb_strtoupper($this->geom)])
            ->andFilterWhere(['like', 'upper(geojson)', mb_strtoupper($this->geojson)])
            ->andFilterWhere(['like', 'upper(ghichu)', mb_strtoupper($this->ghichu)]);

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
        'ten',
        'dientich',
        'dacdiem',
        'diachi',
        'sonha',
        'tenduong',
        'thon',
        'phuongxa',
        'tinhthanh',
        'geom',
        'geojson',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',        ];
    }
}
