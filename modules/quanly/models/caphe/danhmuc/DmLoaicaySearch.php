<?php

namespace app\modules\quanly\models\caphe\danhmuc;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\caphe\danhmuc\DmLoaicay;

/**
 * DmLoaicaySearch represents the model behind the search form about `app\modules\quanly\models\caphe\danhmuc\DmLoaicay`.
 */
class DmLoaicaySearch extends DmLoaicay
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'updated_by', 'created_by', 'nhomcay_id'], 'integer'],
            [['ten', 'layer_geoserver', 'layer_name', 'created_at', 'updated_at'], 'safe'],
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
        $query = DmLoaicay::find()->where(['status' => 1]);

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
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'created_by' => $this->created_by,
            'nhomcay_id' => $this->nhomcay_id,
        ]);

        $query->andFilterWhere(['like', 'upper(ten)', mb_strtoupper($this->ten)])
            ->andFilterWhere(['like', 'upper(layer_geoserver)', mb_strtoupper($this->layer_geoserver)])
            ->andFilterWhere(['like', 'upper(layer_name)', mb_strtoupper($this->layer_name)]);

        return $dataProvider;
    }

    public function getExportColumns()
    {
        return [
            [
                'class' => 'kartik\grid\SerialColumn',
            ],
            'id',
        'ten',
        'layer_geoserver',
        'layer_name',
        'status',
        'created_at',
        'updated_at',
        'updated_by',
        'created_by',
        'nhomcay_id',        ];
    }
}
