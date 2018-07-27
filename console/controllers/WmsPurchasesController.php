<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 2018/6/1
 * Time: 14:52
 */

namespace console\controllers;

use Yii;
use yii\console\Controller;

class WmsPurchasesController extends Controller
{
    public $modelClass = 'common\models\WmsPurchases';
    /**
     * 定时任务生成采购单
     */
    public function actionGeneratePurchaseOrder(){
        $orders = Yii::$app->db->createCommand("select * from oms_order where status in (2)")->queryAll();
        $wms_purchases = new \common\models\WmsPurchases();
        foreach($orders as $order)
        {
            echo $wms_purchases->purchaseOrder($order);
        }
    }

    public function actionTest(){
        $res = $this->modelClass::find()->all();
        var_dump($res);
    }
}