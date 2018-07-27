<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace console\controllers;

use common\models\WmsSoBill;
use yii\console\Controller;

/**

 * 生成拣货单 
 * 系统定时运行后台程序生成拣货波次，同一个库区或相邻库区生归为同一个波次。
 */
class PickRoundController extends Controller
{
    //TODO 现在会重复生成
    public function actionBuild()
    {
        $orders = \Yii::$app->db->createCommand("select * from oms_order where status in (5)")->queryAll();  //待发货的订单
        $group =  array();
        $WmsSoBill = new WmsSoBill();
        foreach ($orders as $key => $order) {
            $skus = \Yii::$app->db->createCommand("select * from oms_order_detail where oid ={$order['id']}")->queryAll(); 

            foreach ($skus as $key => $sku) {
                $order['detail'][$sku['pid']] = $sku;
                //查找产品所在库位 地址
                $sub_inventory_info = \Yii::$app->db->createCommand("select * from wms_sub_inventory_sku as A left join wms_sub_inventory as B on A.sub_inventory_id = B.id where goods_id = {$sku['pid']}")->queryOne(); 
                $order['detail'][$sku['pid']]['code'] = $sub_inventory_info['code'];
                $order['detail'][$sku['pid']]['sub_inventory_id'] = $sub_inventory_info['sub_inventory_id'];
            } 
            //寻找，匹配所在货区的波次
            //待发货状态已经锁定库存，默认有足够库存
            
            $order['location'] = $location = $sub_inventory_info['location'];//一个订单定一个sku位置，以便于拣货
            $group[$order['location']][] = $order;

            //设置拣货中状态 
            $ret = $WmsSoBill->setOrderStatus($order['id'],$before_status=$order['status'],$after_status='6',$remark='系统自动执行波次，修改状态为拣货中',$user_id=0);
        } 
         
        //从分区订单 生成 分区拣货单
        foreach ($group as $g => $gro) {
            $val = ['ref' => 'pr'.date('YmdHis'), 'create_time' => date('Y-m-d H:i:s'), 'warehouse_id' => 1, 'location' => $g, 'print_time'=>0 , 'num' => count($gro) ,'status' => 0];
            \Yii::$app->db->createCommand()->insert('wms_pick_round', $val)->execute();
            $pick_round_id =  \Yii::$app->db->getLastInsertId();

            foreach ($gro as $o => $ord) {
                $val = ['pick_round_id' => $pick_round_id, 'so_bill_id' => $ord['id'], 'order_no' => $ord['order_no'], 'order_info' => json_encode($ord['detail']) ];
                $wms_pick_round_detail =  \Yii::$app->db->createCommand()->insert('wms_pick_round_detail', $val)->execute();
            }
        }

        //捡货中
        echo 'ok'; 
        
    }
}
