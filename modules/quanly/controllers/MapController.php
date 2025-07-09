<?php


namespace app\modules\quanly\controllers;


use app\modules\quanly\base\QuanlyBaseController;
use yii\web\Controller;

class MapController extends QuanlyBaseController
{
    public function actionDuctrong()
    {
        return $this->render('ductrong');
    }

    public function actionGiadinh()
    {
        return $this->render('giadinh');
    }

    public function actionCaphe()
    {
        return $this->render('caphe');
    }

    public function actionMaptest()
    {
        return $this->render('maptest');
    }
     public function actionMapglb()
    {
        return $this->render('mapglb');
    }
}