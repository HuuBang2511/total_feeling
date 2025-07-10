<?php
/**
 * Created by PhpStorm.
 * User: MinhDuc
 * Date: 7/6/2016
 * Time: 9:17 PM
 */

namespace app\modules\services;

use DOMStringExtend;
use hcmgis\user\models\AuthUser;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

class UtilityService {

    const STATUS = [
        'ACTIVE' => 1,
        'DELETED' => 0,
    ];

    public static function alert($content){
        \Yii::$app->session->addFlash($content,true);
        return true;
    }

    public static  function paramValidate($id){
        if (!isset($id) || $id == null ||is_numeric($id) == false) {
            return false;
        } else {
            return true;
        }

    }

    public static function utf8convert($str) {

        $unicode = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ằ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D' => 'Đ',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );
        foreach ($unicode as $nonUnicode => $uni) {
            $str = preg_replace("/($uni)/", $nonUnicode, $str);
        }

        return $str;
    }

    public static function beforeCreate($model){
        if($model->hasAttribute('status')){
            $model->status = self::STATUS['ACTIVE'];
        }

        if($model->hasAttribute('created_at')){
            $model->created_at = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')));
        }

        if($model->hasAttribute('created_by')){
            $model->created_by = (\Yii::$app->user != null) ? \Yii::$app->user->id : 0;
        }
    }

    public static function beforeUpdate($model){

        if($model->hasAttribute('updated_at')){
            $model->updated_at = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')));
        }

        if($model->hasAttribute('updated_by')){
            $model->updated_by = (\Yii::$app->user != null) ? \Yii::$app->user->id : 0;
        }
    }

    public static function convertDateFromDb($date)
    {
        if ($date != null) {
            return date('d/m/Y', strtotime($date));
        } else {
            return '';
        }
    }

    public static function convertAllDatesFromDb($model)
    {
        $dateAttributes = (ArrayHelper::index($model->getTableSchema()->columns, 'name', 'type'))['date'];
        foreach ($dateAttributes as $dateAttribute) {
            $model[$dateAttribute->name] = self::convertDateFromDb($model[$dateAttribute->name]);
        }
        return $model;
    }

    public static function convertDateFromMaskedInput($date)
    {
        if ($date != null) {
            return date('Y-m-d', strtotime(str_replace('/', '-', $date)));
        } else {
            return '';
        }
    }

    public static function convertDateImport($date)
    {
        if ($date != null) {
            return date('d/m/Y', strtotime(str_replace('-', '/', $date)));
        } else {
            return '';
        }
    }

    public static function convertAllDateImport($model)
    {
        $dateAttributes = (ArrayHelper::index($model->getTableSchema()->columns, 'name', 'type'))['date'];
        foreach ($dateAttributes as $dateAttribute) {
            $model[$dateAttribute->name] = self::convertDateImport($model[$dateAttribute->name]);
        }
        return $model;
    }

    public static function convertAllDatesFromMaskedInput($model)
    {
        $dateAttributes = (ArrayHelper::index($model->getTableSchema()->columns, 'name', 'type'))['date'];
        foreach ($dateAttributes as $dateAttribute) {
            $model[$dateAttribute->name] = self::convertDateFromMaskedInput($model[$dateAttribute->name]);
        }
        return $model;
    }

    public static function getRelatedInfo($model = null)
    {
        if ($model != null) {
            return $model->ten;
        } else {
            return '';
        }
    }

    public static function getUserInfo($id)
    {
        return AuthUser::findOne($id)->fullname;
    }

    public static function convertFormat($field, $formatTo, $formatEnd)
    {
        if ($field) {
            $date = \DateTime::createFromFormat($formatTo, $field);
            return $date->format($formatEnd);
        }
        return null;
    }

    public static function generateUuid()
    {
        $data = random_bytes(16);

        // Set version to 4
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);

        // Set variant to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        return vsprintf(
            '%s%s-%s-%s-%s-%s%s%s',
            str_split(bin2hex($data), 4)
        );
    }
}