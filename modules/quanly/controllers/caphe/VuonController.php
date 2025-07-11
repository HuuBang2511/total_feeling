<?php

namespace app\modules\quanly\controllers\caphe;

use Yii;
use app\modules\quanly\models\caphe\Vuon;
use app\modules\quanly\models\caphe\VuonSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\modules\quanly\base\QuanlyBaseController;
use app\modules\quanly\models\caphe\Khuvuc;
/**
 * VuonController implements the CRUD actions for Vuon model.
 */
class VuonController extends QuanlyBaseController
{

    public $title = "Luống";

    public $const;

    public function init(){
        parent::init();
            $this->const = [
            'title' => 'Luống',
            'label' => [
                'index' => 'Danh sách',
                'create' => 'Thêm mới',
                'update' => 'Cập nhật',
                'view' => 'Thông tin chi tiết',
                'statistic' => 'Thống kê',
            ],
            'url' => [
                'index' => 'index',
                'create' => 'Thêm mới',
                'update' => 'Cập nhật',
                'view' => 'Thông tin chi tiết',
                'statistic' => 'Thống kê',
            ],
        ];
    }

    /**
     * Lists all Vuon models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VuonSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single CayBangLangDaTrong model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $geojson = Vuon::find()->select(['st_asgeojson(geom)'])->where(['id' => $id])->asArray()->one();
        
        $geojson = $geojson['st_asgeojson'];

        //dd($geojson);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'geojson' => $geojson,
        ]);
    }

    /**
     * Creates a new CayGaoVangDaTrong model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Vuon();

        $khuvuc = Khuvuc::find()->where(['status' => 1])->all();

        $table = '"vuon"';

        if ($model->load($request->post())) {
            $model->save();

            Yii::$app->db
            ->createCommand("UPDATE ".$table." SET geom = ST_SETSRID(ST_GeomFromText(ST_AsText(ST_GeomFromGeoJSON('" . $model->geojson . "'))),4326) WHERE id = :id")
            ->bindValue(':id', $model->id)
            ->execute();

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'khuvuc' => $khuvuc,
            ]);
        }

    }

    /**
     * Updates an existing CayGaoVangDaTrong model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $khuvuc = Khuvuc::find()->where(['status' => 1])->all();

        $table = '"vuon"';

        //$oldGeomGeojson = $model->geojson;

        if ($model->load($request->post())) {

            Yii::$app->db
                ->createCommand("UPDATE ".$table." SET geom = ST_SETSRID(ST_GeomFromText(ST_AsText(ST_GeomFromGeoJSON('" . $model->geojson . "'))),4326) WHERE id = :id")
                ->bindValue(':id', $model->id)
                ->execute();

            $model->save();

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'khuvuc' => $khuvuc,
            ]);
        }
    }

    /**
     * Delete an existing CayGaoVangDaTrong model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->status = 0;

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Xóa #" . $id,
                    'content' => $this->renderAjax('delete', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Đóng', ['class' => 'btn btn-light float-right', 'data-bs-dismiss' => "modal"]) .
                        Html::button('Xóa', ['class' => 'btn btn-danger float-left', 'type' => "submit"])
                ];
            } else if ($request->isPost && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Xóa thành công #" . $id,
                    'content' => '<span class="text-success">Xóa thành công</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-light float-right', 'data-bs-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => "Update #" . $id,
                    'content' => $this->renderAjax('delete', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-light float-right', 'data-bs-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        }
    }

    
    /**
     * Finds the Vuon model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Vuon the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Vuon::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
