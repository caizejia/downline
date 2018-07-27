<?php

namespace api\modules\v1\wms\controllers;

use Yii;
use common\models\WmsWsBill;
use common\models\WmsWsBillSearch;
//use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use api\modules\v1\wms\controllers\CommonController;

/**
 * WmsWsBillController implements the CRUD actions for WmsWsBill model.
 */
class WmsWsBillController extends CommonController
{

    public $modelClass = 'common\models\WmsWsBill';

    public  function actions()
    {
        $actions = parent::actions();

//        unset($actions['index']);// 以下重写了原来的 index
       return $actions;
    }

    public function actionAlist(){
        $a= [
            'first',
            'second',
            'third'
        ];
        return $a;
    }
    public function actionBlist(){
        $wms= new WmsWsBill();
//        var_dump($wms);die;
        $result= $wms->lis();
        return $result;
    }
}
