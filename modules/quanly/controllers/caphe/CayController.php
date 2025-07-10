<?php

namespace app\modules\quanly\controllers\caphe;

use Yii;
use app\modules\quanly\models\caphe\Cay;
use app\modules\quanly\models\caphe\CaySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\modules\quanly\base\QuanlyBaseController;
use app\modules\quanly\models\caphe\danhmuc\DmLoaicay;
use app\modules\quanly\models\caphe\danhmuc\DmNhomcay;
use app\modules\quanly\models\caphe\Vuon;

/**
 * CayController implements the CRUD actions for Cay model.
 */
class CayController extends QuanlyBaseController
{

    public $title = "Cây trồng";

    public $const;

    public function init(){
        parent::init();
            $this->const = [
            'title' => 'Cây trồng',
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
     * Lists all Cay models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CaySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $loaicay = DmLoaicay::find()->where(['status' => 1])->all();
        $nhomcay = DmNhomcay::find()->where(['status' => 1])->all();
        $vuon = Vuon::find()->where(['status' => 1])->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'loaicay' => $loaicay,
            'nhomcay' => $nhomcay,
            'vuon' => $vuon,
        ]);
    }


    /**
     * Displays a single Cay model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   $model = $this->findModel($id);

        //dd($model->vuon);

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Cay model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Cay();

        $loaicay = DmLoaicay::find()->where(['status' => 1])->all();
        $nhomcay = DmNhomcay::find()->where(['status' => 1])->all();
        $vuon = Vuon::find()->where(['status' => 1])->all();

        $table = '"cay"';

        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->db->createCommand("UPDATE".$table."SET geom = ST_GeomFromText('POINT($model->long"." "."$model->lat)', 4326) WHERE id = :id")
            ->bindValue(':id', $model->id)
            ->execute();

            Yii::$app->db->createCommand("UPDATE".$table."SET geojson = st_asgeojson(ST_GeomFromText('POINT($model->long"." "."$model->lat)', 4326)) WHERE id = :id")
            ->bindValue(':id', $model->id)
            ->execute();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'loaicay' => $loaicay,
                'nhomcay' => $nhomcay,
                'vuon' => $vuon,
            ]);
        }

    }

    /**
     * Updates an existing Cay model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        $loaicay = DmLoaicay::find()->where(['status' => 1])->all();
        $nhomcay = DmNhomcay::find()->where(['status' => 1])->all();
        $vuon = Vuon::find()->where(['status' => 1])->all();


        $table = '"cay"';

        //$oldGeomGeojson = $model->geojson;

        if ($model->load($request->post())) {

            $model->save();

            Yii::$app->db->createCommand("UPDATE".$table."SET geom = ST_GeomFromText('POINT($model->long"." "."$model->lat)', 4326) WHERE id = :id")
            ->bindValue(':id', $model->id)
            ->execute();

            Yii::$app->db->createCommand("UPDATE".$table."SET geojson = st_asgeojson(ST_GeomFromText('POINT($model->long"." "."$model->lat)', 4326)) WHERE id = :id")
            ->bindValue(':id', $model->id)
            ->execute();

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'loaicay' => $loaicay,
                'nhomcay' => $nhomcay,
                'vuon' => $vuon
            ]);
        }
    }

    /**
     * Delete an existing Cay model.
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
     * Finds the Cay model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cay the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cay::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
