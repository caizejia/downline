<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace console\controllers;

use common\models\WmsProductDetails;
use common\models\WmsSoBill;
use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class TransportController extends Controller
{

    /*
     * 命令行自动匹配转运
     */
    public function actionUpdateStock()
    {
        $orders = \Yii::$app->db->createCommand("select * from oms_order where status in (2)")->queryAll();
        $oms_order = new WmsSoBill();
        foreach($orders as $order)
        {
            echo $oms_order->updateStock($order);
        }
    }

    /*
     * 命令行自动匹配库存  这里是密集匹配，尽快把订单匹配发货
     */
    public function actionMatchInventory()
    {
        $orders = \Yii::$app->db->createCommand("select * from oms_order where status in (2)")->queryAll();
        $oms_order = new WmsSoBill();
        foreach($orders as $order)
        {
            echo $oms_order->matchInventory($order);
        }
    }

    public function actionTest(){
        $sku = new WmsProductDetails();
        var_dump($sku = $sku::getGoodsId('A00679PF05000'));
        echo $sku->id;
    }
}

