<?php
/**
 * @link http://www.yiiframework.com/
 *
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace console\controllers;

use app\models\Form;
use app\models\FormSupplier;
use app\models\OrderPackageWz;
use app\models\Orders;
use app\models\Products;
use app\models\TrackLog;
use app\models\Warehouse;
use yii\console\Controller;
use app\models\Corders;
use app\models\Stocks;
use app\models\Trackingmore;
use app\models\ProductSku;
use app\models\OrdersItem;
use app\models\Table;
use Yii;
use app\models\RecordFrom;
use app\models\OrderLogs;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 *
 * @since 2.0
 */
class TrackController extends Controller
{
    /**
     * 从第三方网站获取货态
     */
    public function actionTrack()
    {
        $model = new Corders();

        $orders = $model->find()
            ->andWhere(['NOT IN', 'track_status', ['Delivered', 'Exception']])
//            ->andWhere(['status'=>'已回款'])
            ->andWhere(['NOT IN', 'status', ['已取消', '已签收', '拒签']])
//            ->andWhere('shipment_picked_up_date is null')
            ->andWhere(['IN', 'county', ['MY', 'Malaysia', 'SG', 'Singapore', 'TH', 'ID']])
//            ->andWhere(['IN', 'county', ['MY', 'Malaysia']])
//            ->andWhere(['in', 'county', 'ID'])
//            ->andWhere(['=', 'lc', '商壹'])
//            ->andWhere(['!=', 'lc', '东丰物流'])
            ->andWhere(['is not', 'lc_number', null])
            ->andWhere(['!=', 'lc_number', ''])->all();

        foreach ($orders as $order) {
            //新方法
            $res = $model->getTracking($order);
            echo $order->id.': '.$res."\r\n";
        }
    }

    /**
     * 从官网爬取货态
     *
     * @return bool
     */
    public function actionOldTrack()
    {
        $model = new Corders();
        $stockModel = new Stocks();

        $orders = $model->find()
//            ->andWhere(['NOT IN', 'track_status', ['Delivered', 'Exception']])
//            ->andWhere(['NOT IN', 'status', ['已取消']])
//            ->andWhere(['IN', 'county', ['MY', 'Malaysia', 'SG', 'Singapore', 'TH']])
//            ->andWhere(['IN', 'county', ['臺灣']])
//            ->andWhere(['IN', 'county', ['UAE']])
//            ->andWhere(['is','pickup_date', null])
            ->andWhere(['=', 'lc', 'AFL'])
//            ->andWhere(['!=', 'lc', '东丰物流'])
            ->andWhere(['is not', 'lc_number', null])
            ->andWhere(['!=', 'lc_number', ''])->all();

        foreach ($orders as $order) {
            echo "\n".$order->id.': ';
            $track = $model->updateeTrack($order);
            if ($track) {
                //                var_dump($track);die;
                echo $track['status'];
                $order->track_status = $track['status'];
                $order->dd_fail = $track['dd_fail'];
                $order->address_error = $track['address_error'];
                if ($track['pickup_date']) {
                    $order->pickup_date = $track['pickup_date'];
                }
                if ($track['delivery_date']) {
                    $order->delivery_date = $track['delivery_date'];
                }
                if ($track['status'] == 'Delivered') {
                    $order->status = '已签收';
                    $stock_order = $stockModel->find()->where(['new_order_id' => $order->id])->one();
                    if ($stock_order) {
                        $stock_order->status = 3;
                        $stock_order->save();
                    }
                }
                if ($track['status'] == 'Exception') {
                    $order->status = '拒签';
                    $stock_order = $stockModel->find()->where(['new_order_id' => $order->id])->one();
                    if ($stock_order) {
                        $stock_order->status = 4;
                        $stock_order->save();
                    }
                }
                if ($order->save()) {
                    if ($track['status'] == '已签收' || $track['status'] == '拒签') {
                        $log = new OrderLogs();
                        $log->attributes = [
                            'order_id' => $order->id,
                            'status' => $order->status,
                            'user_id' => 1,
                            'create_date' => date('Y-m-d'),
                            'comment' => '抓取物流改状态为'.$track['status'],
                        ];
                        $log->save();
                    }
                }
            }
        }
    }

    //全部数据更新使用下面方法

    /**
     * 从第三方网站获取货态更新全部.
     */
    public function actionTrackUpdate()
    {
        $model = new Corders();

        $orders = $model->find()
//            ->andWhere(['NOT IN', 'track_status', ['Delivered', 'Exception']])
//            ->andWhere(['status'=>'已回款'])
            ->andWhere(['NOT IN', 'status', ['已取消']])
//            ->andWhere('shipment_picked_up_date is null')
            ->andWhere(['IN', 'county', ['MY', 'Malaysia', 'SG', 'Singapore', 'TH']])
//            ->andWhere(['IN', 'county', ['MY', 'Malaysia']])
//            ->andWhere(['in', 'county', 'ID'])
            ->andWhere(['=', 'lc', '商壹'])
//            ->andWhere(['!=', 'lc', '东丰物流'])
            ->andWhere(['is not', 'lc_number', null])
            ->andWhere(['!=', 'lc_number', ''])->all();

        foreach ($orders as $order) {
            //新方法
            $res = $model->getTracking($order);
            echo $order->id.': '.$res."\r\n";
        }
    }

    /**
     * 从官网爬取货态更新全部.
     */
    public function actionOldTrackUpdate()
    {
        $model = new Corders();

        $orders = $model->find()
            ->andWhere(['NOT IN', 'track_status', ['Delivered', 'Exception']])
            ->andWhere(['NOT IN', 'status', ['已取消', '待确认', '已签收', '拒签']])
//            ->andWhere(['IN', 'county', ['MY', 'Malaysia', 'SG', 'Singapore', 'TH', 'HK', '臺灣']])
//            ->andWhere(['IN', 'county', ['SG', 'Singapore', 'MY', 'Malaysia']])
//            ->andWhere(['=', 'lc', 'K1'])
//            ->andWhere(['!=', 'lc', '东丰物流'])
//            ->andWhere('id>=50015341')
//            ->andWhere("lc_number LIKE 'TTI%'")->all();
            ->andWhere(['is not', 'lc_number', null])
            ->andWhere(['!=', 'lc_number', ''])->all();

        foreach ($orders as $order) {
            echo "\n".$order->id.': ';
            $track = $model->updateTrack($order);
            echo $track['status'];
        }
    }

    /**
     * 云路track log 获取.
     */
    public function actionYlTrackUpdate()
    {
        $model = new Corders();

        $orders = $model->find()
            ->andWhere(['NOT IN', 'track_status', ['Delivered', 'Exception']])
//            ->andWhere(['status'=>'已回款'])
            ->andWhere(['NOT IN', 'status', ['已取消']])
//            ->andWhere('shipment_picked_up_date is null')
            ->andWhere(['IN', 'county', ['ID']])
//            ->andWhere(['IN', 'county', ['MY', 'Malaysia']])
//            ->andWhere(['in', 'county', 'ID'])
            ->andWhere(['IN', 'lc', ['汉邮', '云路']])
//            ->andWhere(['!=', 'lc', '东丰物流'])
            ->andWhere(['is not', 'lc_number', null])
            ->andWhere(['!=', 'lc_number', ''])->all();

        foreach ($orders as $order) {
            //新方法
            echo $order->id.': ';
            $res = $model->getTracking($order);
            echo $res."\r\n";
        }
    }

    /**
     * 商壹货态日志.
     */
    public function actionComTrackLog()
    {
        $model = new Corders();

        $orders = $model->find()
            ->andWhere(['NOT IN', 'track_status', ['Delivered', 'Exception']])
            ->andWhere(['NOT IN', 'status', ['已取消']])
            ->andWhere(['IN', 'county', ['MY', 'Malaysia', 'SG', 'Singapore', 'TH']])
//            ->andWhere(['IN', 'county', [ 'SG', 'Singapore']])
            ->andWhere(['=', 'lc', '商壹'])
//            ->andWhere(['!=', 'lc', '东丰物流'])
//            ->andWhere('id=80025370	')
//            ->andWhere("lc_number LIKE '744%'")->all();
            ->andWhere(['is not', 'lc_number', null])
            ->andWhere(['!=', 'lc_number', ''])->all();

        foreach ($orders as $order) {
            echo "\n".$order->id.': ';
            $track = $model->updateTrack($order);
            echo $track['status'];
        }
    }

    /**
     * TTi货态日志.
     */
    public function actionTtiTrackLog()
    {
        $model = new Corders();

        $orders = $model->find()
            ->andWhere(['NOT IN', 'track_status', ['Delivered', 'Exception']])
            ->andWhere(['NOT IN', 'status', ['已取消']])
            ->andWhere(['IN', 'county', ['TH']])
            ->andWhere(['=', 'lc', 'TTI'])
//            ->andWhere('id>=50015341')
//            ->andWhere("lc_number LIKE '744%'")->all();
            ->andWhere(['is not', 'lc_number', null])
            ->andWhere(['!=', 'lc_number', ''])->all();

        foreach ($orders as $order) {
            echo "\n".$order->id.': ';
            $track = $model->updateTrack($order);
            echo $track['status'];
        }
    }

    /**
     * 1+1贷态日志.
     */
    public function actionOneTrackLog()
    {
        $model = new Corders();

        $orders = $model->find()
            ->andWhere(['NOT IN', 'track_status', ['Delivered', 'Exception']])
            ->andWhere(['NOT IN', 'status', ['已取消']])
//            ->andWhere(['IN', 'county', ['MY', 'Malaysia', 'SG', 'Singapore', 'TH']])
            ->andWhere(['IN', 'county', ['MY', 'Malaysia', 'SG', 'Singapore']])
//            ->andWhere(['is','pickup_date', null])
            ->andWhere(['=', 'lc', '1+1'])
//            ->andWhere(['=', 'id', 50014305])
//            ->andWhere("lc_number LIKE '745%'")->all();
            ->andWhere(['is not', 'lc_number', null])
            ->andWhere(['!=', 'lc_number', ''])->all();

        foreach ($orders as $order) {
            echo "\n".$order->id.': ';
            $track = $model->updateTrack($order);
            echo $track['status'];
        }
    }

    /**
     * CDS货态日志.
     */
    public function actionCdsTrackLog()
    {
        $model = new Corders();

        $orders = $model->find()
            ->andWhere(['NOT IN', 'track_status', ['Delivered', 'Exception']])
//            ->andWhere(['NOT IN', 'status', ['已取消']])
//            ->andWhere(['IN', 'county', ['MY', 'Malaysia', 'SG', 'Singapore', 'TH']])
//            ->andWhere(['is','pickup_date', null])
            ->andWhere(['=', 'lc', 'CDS'])
//            ->andWhere('id>=80003581')
            ->andWhere(['is not', 'lc_number', null])
            ->andWhere(['!=', 'lc_number', ''])->all();

        foreach ($orders as $order) {
            echo "\n".$order->id.': ';
            switch ($order->county) {
                case 'ID':
                    $track = $model->getTracking($order);
                    echo $track;
                    break;
                case 'MY':
                    $track = $model->updateTrack($order);
                    echo $track['status'];
                    break;
            }
        }
    }

    /**
     * TJM货态日志.
     */
    public function actionTjmTrackLog()
    {
        $model = new Corders();

        $orders = $model->find()
            ->andWhere(['NOT IN', 'track_status', ['Delivered', 'Exception']])
//            ->andWhere(['NOT IN', 'status', ['已取消']])
//            ->andWhere(['IN', 'county', ['MY', 'Malaysia', 'SG', 'Singapore', 'TH']])
//            ->andWhere(['is','pickup_date', null])
            ->andWhere(['=', 'lc', 'TJM'])
            ->andWhere(['is not', 'lc_number', null])
            ->andWhere(['!=', 'lc_number', ''])->all();

        foreach ($orders as $order) {
            echo "\n".$order->id.': ';
            switch ($order->county) {
                case 'MY':
                    $track = $model->updateTrack($order);
                    echo $track['status'];
                    break;
            }
        }
    }

    /**
     * 易速配货态日志.
     */
    public function actionYspTrackLog()
    {
        $model = new Corders();

        $orders = $model->find()
            ->andWhere(['NOT IN', 'track_status', ['Delivered', 'Exception']])
//            ->andWhere(['NOT IN', 'status', ['已取消']])
//            ->andWhere(['IN', 'county', ['MY', 'Malaysia', 'SG', 'Singapore', 'TH']])
//            ->andWhere(['is','pickup_date', null])
            ->andWhere(['=', 'lc', '易速配'])
            ->andWhere(['is not', 'lc_number', null])
            ->andWhere(['!=', 'lc_number', ''])->all();

        foreach ($orders as $order) {
            echo "\n".$order->id.': ';
            switch ($order->county) {
                case '臺灣':
                    $track = $model->updateTrack($order);
                    echo $track['status'];
                    break;
            }
        }
    }

    /**
     * 宅急便货态日志.
     */
    public function actionTcatTrackLog()
    {
        $model = new Corders();

        $orders = $model->find()
            ->andWhere(['NOT IN', 'track_status', ['Delivered', 'Exception']])
//            ->andWhere(['NOT IN', 'status', ['已取消']])
//            ->andWhere(['IN', 'county', ['MY', 'Malaysia', 'SG', 'Singapore', 'TH']])
//            ->andWhere(['is','pickup_date', null])
            ->andWhere(['=', 'lc', '1+1'])
            ->andWhere(['is not', 'lc_number', null])
            ->andWhere(['!=', 'lc_number', ''])->all();

        foreach ($orders as $order) {
            echo "\n".$order->id.': ';
            switch ($order->county) {
                case '臺灣':
                    $track = $model->updateTrack($order);
                    echo $track['status'];
                    break;
            }
        }
    }

    /**
     * K1货态获取.
     */
    public function actionKoneTrackLog()
    {
        $model = new Corders();

        $orders = $model->find()
            ->andWhere(['NOT IN', 'track_status', ['Delivered', 'Exception']])
//            ->andWhere(['NOT IN', 'status', ['已取消']])
//            ->andWhere(['IN', 'county', ['MY', 'Malaysia', 'SG', 'Singapore', 'TH']])
//            ->andWhere(['is','pickup_date', null])
            ->andWhere(['=', 'lc', 'K1'])
            ->andWhere(['is not', 'lc_number', null])
            ->andWhere(['!=', 'lc_number', ''])->all();

        foreach ($orders as $order) {
            echo "\n".$order->id.': ';
            switch ($order->county) {
                case 'MY':
                    $track = $model->updateTrack($order);
                    echo $track['status'];
                    break;
            }
        }
    }

    /**
     * 测试用.
     */
    public function actionTest()
    {
        $track = new Trackingmore();
        $output = $track->getRealtimeTrackingResults('dhlecommerce-asia', 'MYAMB7450741513');
        print_r($output);
    }

    public function actionId()
    {
        $model = new Orders();
        $logModel = new TrackLog();
        $stockModel = new Stocks();

        $orders = $model->find()
            ->andWhere(['NOT IN', 'track_status', ['Delivered', 'Exception']])
            ->andWhere(['IN', 'county', ['ID']])
            ->andWhere(['is not', 'lc_number', null])
            ->andWhere(['!=', 'lc_number', ''])->all();
        foreach ($orders as $order) {
            $stock_order = $stockModel->find()->where(['new_order_id' => $order->id])->one();
            echo $order->lc_number."\n";
            $logs = $this->jet($order);
            foreach ($logs as $log2) {
                foreach ($log2 as $log) {
                    $logModel->setIsNewRecord(true);
                    unset($logModel->id);
                    $track_date = $log['track_date'];
                    $md5 = md5(implode($log));
                    $logModel->attributes = [
                        'order_id' => $log['order_id'],
                        'track_date' => $track_date,
                        'track_status' => $log['track_status'],
                        'md5' => $md5,
                    ];
                    if (!$logModel->find()->where(['md5' => $md5])->one()) {
                        $logModel->save();
                    }

                    if ($log['track_status'] == 'paket sudah sampai di tujuan【SALES_DEPARTMENT】') {
                        $order->status = '拒签';
                        $order->delivery_date = $track_date;
                        $order->track_status = 'Exception';
                        if ($stock_order) {
                            $stock_order->status = 4;
                            $stock_order->save();
                        }
                        $order->save();

                        $log = new OrderLogs();
                        $log->attributes = [
                            'order_id' => $order->id,
                            'status' => (Orders::find()->where(['id' => $order->id])->one())->status,
                            'user_id' => Yii::$app->user->id,
                            'create_date' => date('Y-m-d'),
                            'comment' => '抓取物流改状态为'.$order->status,
                        ];
                        $log->save();
                    }
                    if (strpos($log['track_status'], 'sudah diterima') !== false) {
                        $order->status = '已签收';
                        $order->delivery_date = $track_date;
                        $order->track_status = 'Delivered';
                        if ($stock_order) {
                            $stock_order->status = 3;
                            $stock_order->save();
                        }
                        $order->save();

                        $log = new OrderLogs();
                        $log->attributes = [
                            'order_id' => $order->id,
                            'status' => (Orders::find()->where(['id' => $order->id])->one())->status,
                            'user_id' => Yii::$app->user->id,
                            'create_date' => date('Y-m-d'),
                            'comment' => '抓取物流改状态为'.$order->status,
                        ];
                        $log->save();
                    }
                }
            }
            sleep(1);
        }
    }

    /**
     * 更新SKU.
     */
    public function actionUpdateSku()
    {
        $skuModel = new ProductSku();
        $itemModel = new OrdersItem();
        $items = $itemModel->find()->where(['>', 'create_date', '2018-01-17'])->andWhere(['is', 'sku', null])->all();
        $fp = fopen('./update-sku.csv', 'w+');
        foreach ($items as $item) {
            echo "Update order {$item->order_id} SKU:";
            $sign = md5(trim($item->size).trim(str_replace(['(1)', '(2)', '(3)', '(4)', '(5)', '(6)', '(7)', '(8)', '(9)', '(10)'], '', $item->color)));
            $order = Orders::findOne($item->order_id);
            if ($order) {
                $sku = $skuModel->find()->where(['=', 'p_id', $order->website])->andWhere(['=', 'sign', $sign])->one();
                if ($sku) {
                    $item->sku = $sku->sku;
                    echo $item->save();
                } else {
                    $str = $item->size.'-'.$item->color.'==='.$order->website;
                    echo $str;
                    fwrite($fp, $item->order_id.':'.$str."\r\n");
                }
            } else {
            }
            echo "\n";
        }
        fclose($fp);
    }

    /**
     * 更新上网时间.
     */
    public function actionUpdatePickdate()
    {
        $orderModel = new Corders();
        $orders = $orderModel->find()->where(['<>', 'status', '已取消'])->all();
        $model = new TrackLog();
        foreach ($orders as $order) {
            $track = $model->find()->where(['=', 'order_id', $order->id])->orderBy(['track_date' => SORT_ASC])->one();
            if ($track && empty($order->pickup_date)) {
                $order->pickup_date = $track->track_date;
                echo $order->save();
            }
        }
    }

    /**
     * 更新所有产品为缺货.
     */
    public function actionOrderStatus()
    {
        $formModel = new Form();
        $status = $formModel->find()->where(['status' => 2])->asArray()->all();

        foreach ($status as $v) {
            $record[] = RecordFrom::find()->where(['t_id' => $v['t_id'], 'f_id' => $v['id']])->asArray()->all();
        }
        $date = array_filter($record);
        foreach ($date as $key => $v) {
            $new_arr[] = $v[0];
        }
        $older_id = array_column($new_arr, 'o_id');
        //批量修改状态
        foreach ($older_id as $v3) {
            echo "\nUpdate {$v3}";
            echo Corders::updateAll(['status' => '缺货'], "status = '已采购' AND id = '{$v3}'");
            //exit();
        }
    }

    /**
     * 生成采购单.
     *
     * @return bool
     *
     * @throws \yii\db\Exception
     */
    public function actionCreatePurchase()
    {
        ini_set('date.timezone', 'Asia/Shanghai');
        $tabelModel = new Table();
        $time_end = date('Y-m-d H:00:00');
        echo $time_end;
        $table = $tabelModel->findOne(['name' => $time_end]);
        $orderModel = new Corders();
        if (empty($table)) {
            echo '生成采购单';
            //生成采购单
            $tabelModel->name = $time_end;
            $tabelModel->datetime = time();
            $tabelModel->admin_id = 1;
            $tabelModel->is_detail = 1;
            if ($tabelModel->save()) {
                $tabelModel = Table::findOne(['id' => $tabelModel->id]);
                if ($tabelModel) {
                    $str = str_replace(' ', '', date('Ymd ', $tabelModel->datetime).$tabelModel->id);
                    echo '生成订单编号'.$str;
                    Table::updateAll(['number' => $str], ['id' => $tabelModel->id]);
                }
                //添加采购单详情
                $orderModel->addPurchaseInfo($tabelModel->id, $time_end);
            } else {
                var_dump($tabelModel->getErrors());
            }
        } else {
            $orderModel->addPurchaseInfo($table->id, $time_end);
        }
    }

    /**
     * ip 换成地址
     */
    public function actionIp()
    {
        $model = new Corders();
        $orders = $model->find()->select('ip,id')->where(['ip_a' => '0'])->orderBy('id DESC')->all();
        $i = 0;
        foreach ($orders as $order) {
            $ips = $order->ip;
            $ip = @file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip='.$ips);
            $ip = json_decode($ip, true);
            if (!(Yii::$app->db->createCommand("select id from ip where ip='$ips' and order_id = '$order->id'")->queryOne())) {
                Yii::$app->db->createCommand()->update('orders', ['ip_a' => '1'], "id = {$order->id}")->execute();
                Yii::$app->db->createCommand()->insert('ip', ['code' => $ip['code'], 'ip' => $ip['data']['ip'], 'country' => $ip['data']['country'], 'area' => $ip['data']['area'], 'region' => $ip['data']['region'], 'city' => $ip['data']['city'], 'county' => $ip['data']['county'], 'isp' => $ip['data']['isp'], 'country_id' => $ip['data']['country_id'], 'area_id' => $ip['data']['area_id'], 'region_id' => $ip['data']['region_id'], 'city_id' => $ip['data']['city_id'], 'county_id' => $ip['data']['county_id'], 'isp_id' => $ip['data']['isp_id'], 'order_id' => $order->id])->execute();
                echo $i.'插入成功'."\n";
            } else {
                echo $i.'已存在，忽略'."\n";
            }
            ++$i;
        }
        echo '更新完毕';
    }

    /**
     * 批量修改货代.
     */
    public function actionUpdateTrackNume()
    {
        $model = new Corders();
        $orders = $model->find()->where(['is', 'lc', null])->andWhere(['in', 'status', ['已确认', '已采购']])->all();
        foreach ($orders as $order) {
            echo 'Update track name:';
            echo $order->updateTrackName();
            echo "\n";
        }
    }

    /**
     * 检查库存更新订单状态为可发货.
     */
    public function actionCheckOrderStock()
    {
        $model = new Orders();
        $stockModel = new Warehouse();
        $orders = $model->find()->where(['in', 'status', ['已采购', '已确认']])->andWhere("county!='ID' AND county!='PHL' AND county!='LKA'")->orderBy('id ASC')->all();
//        $orders = $model->find()->where(['in', 'status', ['待发货']])->all();
        foreach ($orders as $order) {
            echo $order->id."\n";
            $stock = true;
            $sku_qty = [];
            foreach ($order->items as $item) {
                //                echo $item->sku;
                if (isset($sku_qty[$item->sku])) {
                    $sku_qty[$item->sku] += $item->qty;//相同SKU合计数量
                } else {
                    $sku_qty[$item->sku] = $item->qty;
                }

                if ($stock) {
                    echo $item->sku;
                    echo '=';
                    echo $sku_qty[$item->sku];
                    $stock = $stockModel->checkStock($item->sku, $sku_qty[$item->sku]);
                    var_dump($stock);
                }
            }

            if ($stock) {
                $comment = '系统执行：订单状态由['.$order->status.']改为[待发货]';
                $logModel = new OrderLogs();
                $logModel->attributes = [
                    'order_id' => $order->id,
                    'status' => '待发货',
                    'user_id' => 1,
                    'comment' => $comment,
                ];
                $logModel->save();

                $order->status = '待发货';
                $order->on_shipping_time = date('Y-m-d H:i:s');
//                $order->outStock(true);
                //查询是否已打包成ID, 处理完，取消这个功能
                $order->getIdOrder();
                echo $order->save();
            } else {
            }
        }
    }

    /**
     * ID检查库存更新订单状态为可发货.
     */
    public function actionCheckOrderStockId()
    {
        $model = new Orders();
        $stockModel = new Warehouse();
        $orders = $model->find()->where(['in', 'status', ['已采购', '已确认']])->andWhere("county='ID' AND create_date>'2018-05-11'")->all();
//        $orders = $model->find()->where(['in', 'status', ['待发货']])->all();
        foreach ($orders as $order) {
            echo $order->id."\n";
            $stock = true;
            $sku_qty = [];
            foreach ($order->items as $item) {
                //                echo $item->sku;
                if (isset($sku_qty[$item->sku])) {
                    $sku_qty[$item->sku] += $item->qty;//相同SKU合计数量
                } else {
                    $sku_qty[$item->sku] = $item->qty;
                }

                if ($stock) {
                    echo $item->sku;
                    echo '=';
                    echo $sku_qty[$item->sku];
                    $stock = $stockModel->checkStock($item->sku, $sku_qty[$item->sku]);
                    var_dump($stock);
                }
            }

            if ($stock) {
                $comment = '系统执行：订单状态由['.$order->status.']改为[待发货]';
                $logModel = new OrderLogs();
                $logModel->attributes = [
                    'order_id' => $order->id,
                    'status' => '待发货',
                    'user_id' => 1,
                    'comment' => $comment,
                ];
                $logModel->save();

                $order->status = '待发货';
                $order->on_shipping_time = date('Y-m-d H:i:s');
//                $order->outStock(true);
                //查询是否已打包成ID, 处理完，取消这个功能
                $order->getIdOrder();
                echo $order->save();
            } else {
            }
        }
    }

    /**
     * ID已打包匹配.
     */
    public function actionCheckIdStock()
    {
        $model = new Orders();
        $stockModel = new Warehouse();
        $orders = $model->find()->where(['in', 'status', ['待发货']])->andWhere("county!='ID'")->andWhere(['id' => 80002537])->all();
//        $orders = $model->find()->where(['in', 'status', ['待发货']])->all();
        foreach ($orders as $order) {
            echo $order->id."\n";
            $stock = true;
            foreach ($order->items as $item) {
                //                echo $item->sku;
                if ($stock) {
                    $stock = $stockModel->checkStock($item->sku, $item->qty);
                }
            }

            if ($stock) {
                //查询是否已打包成ID
                $order->getIdOrder();
                echo $order->save();
            }
        }
    }

    /*
    * 批量更新订单单号
    */
    public function actionNumber()
    {
        $tableModel = new Table();
        $numberTable = $tableModel->find()->select('id,datetime')->where(['number' => null])->all();
        foreach ($numberTable as $value) {
            $str = str_replace(' ', '', date('Ymd ', $value->datetime).$value->id);
            Table::updateAll(['number' => $str], ['id' => $value->id]);
            echo '更新'.$str;
        }
    }

    /**
     * 取消掉2月23-26号采购单.
     */
    public function actionOrderStatues()
    {
        $data = Table::find()->select('id')->Where(['between', 'datetime', 1519318800, 1519638447])->asArray()->all();
        $data2 = array_column($data, 'id');
        foreach ($data2 as $v) {
            $form = RecordFrom::find()->where(['t_id' => $v])->asArray()->all();
            foreach ($form as $v2) {
                $order_id[] = $v2['o_id'];
                foreach ($order_id as $v3) {
                    Orders::updateAll(['status' => '已确认'], "status != '待发货' AND id = '{$v3}'");
                    echo '修改已确认'.$v3.'<br/>';
                }
            }
        }
    }

    /**
     * 删除2月23-26号采购单.
     */
    public function actionDetailTable()
    {
        //批量删除
        $data = Table::find()->select('id')->Where(['between', 'datetime', 1519318800, 1519638447])->asArray()->all();
        $data2 = array_column($data, 'id');
        foreach ($data2 as $v) {
            Table::deleteAll(['id' => $v]);
            Form::deleteAll(['t_id' => $v]);
        }
    }

    /**
     * 更新时间.
     */
    public function actionUpDate()
    {
        $data = array(80003499, 80003488, 80003477, 80003440, 80002925, 80002916, 80002911, 80002905, 80002874, 80002828, 80002827, 80002817, 80002814, 80002811, 80002786, 80002781, 80002778, 80002761, 80001985, 80001983, 80001982, 80001971, 80001962, 80001960, 80001957, 80001949, 80001944, 80001941, 80001932, 80001923, 80001913, 80001912, 80001908, 80001905, 80001903, 80001899, 80001898, 80001890, 80001882, 80001874, 80001872, 80001857, 80001843, 80001842, 80001795, 80001787, 80001776, 80001772, 80001768, 80001763, 80001760, 80001758, 80001757, 80001752, 80001746, 80001721, 80001695, 80001687, 80001683, 80001681, 80001678, 80001667, 80001586, 80001009, 80000993, 80000977, 80000972, 80000964, 80000948, 80000947, 80000933, 80000922, 80000920, 80000917, 80000898, 80000890, 80000889, 80000888, 80000887, 80000883, 80000882, 80000881, 80000880, 80000878, 80000877, 80000875, 80000872, 80000867, 80000863, 80000860, 80000858, 80000852, 80000847, 80000846, 80000841, 80000840, 80000824);
        foreach ($data as $v) {
            if ($orderData = Orders::findOne($v)) {
                Orders::updateAll(['create_date' => $orderData['confirm_time']], ['id' => $v]);
                echo '更新订单'.$v.'时间'.$orderData['confirm_time']."\n";
            } else {
                echo '更新失败'.$v.'时间'."\n";
            }
        }
    }

    /**
     * 更新订单采购时间.
     */
    public function actionPurchase()
    {
        $recordData = RecordFrom::find()->all();
        foreach ($recordData as $v) {
            $form = Form::find()->where(['id' => $v->f_id])->one();
            Orders::updateAll(['purchase_time' => date('Y-m-d H:i:s', $form['purchase_time'])], ['id' => $v->o_id]);
            echo '更新订单'.$v->o_id.'时间'.$form['purchase_time']."\n";
        }
    }

    /*
     * 更新所有重新生成的订单
     */
    public function actionCreateDate()
    {
        $data = Yii::$app->db->createCommand("select * from orders where id like '800%' and `create_date` >= '2018-01-01' and create_date < '2018-03-03'")->queryAll();
        foreach ($data as $v) {
            $orders = Orders::find()->where(['id' => $v['id']])->one();
            Orders::updateAll(['create_date' => $orders['confirm_time']], ['id' => $v['id']]);
            echo '更新订单'.$v['id'].'时间'.$orders['confirm_time']."\n";
        }
    }

    /*
     * 生成所有的供应商
     */
    public function actionFormSupplier()
    {
        $supplierModel = new FormSupplier();
        $formData = Form::find()->where(['>', 'id', 160])->all();
        foreach ($formData as $v) {
            $supplierData = FormSupplier::find()->where(['f_id' => $v->id])->count();
            if ($supplierData == 0) {
                $supplierModel->f_id = $v['id'];
                $supplierModel->supplier = $v['supplier'];
                $supplierModel->supplier_name = $v['supplier_name'];
                $supplierModel->order_number = $v['order_number'];
                $supplierModel->freight = $v['freight'];
                $supplierModel->arrival_date = $v['arrival_date'];
                $supplierModel->number = $v['number'];
                $supplierModel->link = $v['link'];
                $supplierModel->cost = $v['cost'];
                $supplierModel->logistics = $v['logistics'];
                $supplierModel->pay = $v['pay'];
                $supplierModel->invoice = $v['invoice'];
                $supplierModel->status = $v['status'];
                $supplierModel->user_id = 2;
                $supplierModel->create_time = date('Y-m-d H:i:s');
                $supplierModel->payment = $v['payment'];
                $supplierModel->payment_time = $v['payment_time'];
                $supplierModel->payment_user = $v['payment_user'];
                $supplierModel->actual_number = $v['actual_number'];
                $supplierModel->comment = $v['comment'];
                $supplierModel->setIsNewRecord(true);
                unset($supplierModel->id);
                $supplierModel->save();
                echo '更新'.$v->id."\n";
            }
        }
    }
    /*
      * 生成
      */
    public function actionTableSupplier()
    {
        $supplierModel = new FormSupplier();
        $formData = Form::find()->where(['=', 't_id', 295])->all();
        foreach ($formData as $v) {
            $supplierData = FormSupplier::find()->where(['f_id' => $v->id])->count();
            if ($supplierData == 0) {
                $supplierModel->f_id = $v['id'];
                $supplierModel->supplier = $v['supplier'];
                $supplierModel->supplier_name = $v['supplier_name'];
                $supplierModel->order_number = $v['order_number'];
                $supplierModel->freight = $v['freight'];
                $supplierModel->arrival_date = $v['arrival_date'];
                $supplierModel->number = $v['number'];
                $supplierModel->link = $v['link'];
                $supplierModel->cost = $v['cost'];
                $supplierModel->logistics = $v['logistics'];
                $supplierModel->pay = $v['pay'];
                $supplierModel->invoice = $v['invoice'];
                $supplierModel->status = $v['status'];
                $supplierModel->user_id = 2;
                $supplierModel->create_time = date('Y-m-d H:i:s');
                $supplierModel->payment = $v['payment'];
                $supplierModel->payment_time = $v['payment_time'];
                $supplierModel->payment_user = $v['payment_user'];
                $supplierModel->actual_number = $v['actual_number'];
                $supplierModel->comment = $v['comment'];
                $supplierModel->setIsNewRecord(true);
                unset($supplierModel->id);
                $supplierModel->save();
                echo '更新'.$v->id."\n";
            }
        }
    }

    /**
     *更新国外物流商道订单表
     * TH: kerryexpress, ninjavan
     * MY: DHL, GDEX
     * ID: J&T.
     */
    public function actionUpdateLc()
    {
        $model = new Orders();
        $orders = $model->find()
            ->andWhere(['is not', 'lc_number', null])
//            ->andWhere("lc_foreign = 'other'")
            ->andWhere("lc_foreign IS NULL or lc_foreign = 'other'")
            ->andWhere(['!=', 'lc_number', ''])->all();
        foreach ($orders as $v) {
            $track_number = $v->lc_number;
            $country = $v->county;
            $action = '';
            if (strtoupper(substr($track_number, 0, 5)) == 'PFCSG') {
                $action = 'ninjavan';
            } elseif ($country == 'TH' && $v->lc == '博佳图') {
                $action = 'kerryexpress';
            } elseif ($country == 'ID' && $v->lc == '云路') {
                $action = 'J&T';
            } elseif ($country == 'ID' && $v->lc == 'CDS') {
                $action = 'JNE';
            } elseif ($country == 'TH' && $v->lc == 'TTI') {
                $action = 'kerryexpress';
            } elseif ($country == 'MY' && $v->lc == '1+1' && 'YJYMY' == strtoupper(substr($track_number, 0, 5))) {
                $action = 'ninjavan';
            } elseif ($country == 'ID' && $v->lc == '汉邮') {
                $action = 'J&T';
            } elseif ($country == 'MY' && $v->lc == 'CDS') {
                $action = 'dhl';
            } elseif (strtoupper(substr($track_number, 0, 5)) == 'PFCMY') {
                $action = 'ninjavan';
            } elseif (substr($track_number, 0, 3) == '744') {
                $action = 'ninjavan';
            } elseif ($country == 'SG' && '745' == substr($track_number, 0, 3)) {
                $action = 'ninjavan';
            } elseif (strtoupper(substr($track_number, 0, 3)) == 'MYA') {
                $action = 'dhl';
            } elseif ($country == 'MY' && '745' == substr($track_number, 0, 3)) {
                $action = 'dhl';
            } elseif ($country == 'TH' && strtoupper(substr($track_number, 0, 4)) == 'SOAR') {
                $action = 'kerryexpress';
            } elseif ($country == 'TH' && (strtoupper(substr($track_number, 0, 3)) == 'PFC' || is_numeric($track_number))) {
                $action = $this->getLc($v);
            } elseif ('TH' == $country && 'TTI' == strtoupper(substr($track_number, 0, 3))) {
                $action = 'kerryexpress';
            } elseif ('1+1' == $v->lc && 'MY' == $country) {
                $action = $this->getLc($v);
            } elseif ('1+1' == $v->lc && 'SG' == $country) {
                $action = $this->getLc($v);
            } elseif ('MY' == $country && '87' == substr($track_number, 0, 2)) {
                $action = $this->getLc($v);
            } elseif ($v->lc == '东丰物流' && $country == 'MY') {
                $action = 'dhl';
            } elseif ('YJY' == substr($track_number, 0, 3)) {
                $action = 'gdex';
            } elseif ($v->county == '臺灣' && $v->lc == '易速配') {
                $action = 'post_tw';
            } elseif ($v->lc == 'AFL') {
                $action = 'SAP';
            } elseif ($v->lc == '合联') {
                $action = 'FFC';
            } elseif ($v->lc == '和洋运通' && $country == 'PHL') {
                $action = 'ninjavan';
            } elseif ($v->lc == 'K1') {
                $action = 'dhl';
            } else {
                $action = null;
            }
            if ($action) {
                Yii::$app->db->createCommand()->update('orders', ['lc_foreign' => $action], "id = '{$v->id}'")->execute();
            }
        }
    }

    /**
     * 获取国外承运商.
     *
     * @param $order
     *
     * @return string
     */
    public function getLc($order)
    {
        $track_number = $order->lc_number;
        if ('1+1' == $order->lc && 'MY' == $order->county) {
            if ('YJY' == substr($track_number, 0, 3)) {
                return 'other';
            } else {
                return 'gdex';
            }
        } elseif ('1+1' == $order->lc && 'SG' == $order->county) {
            return 'roadbull';
        } elseif ('商壹' == $order->lc && 'MY' == $order->county && '87' == substr($order->lc_number, 0, 2)) {
            return 'gdex';
        } elseif ('商壹' == $order->lc && $order->county == 'MY' && '745' == substr($order->lc_number, 0, 3)) {
            return 'dhl';
        } else {
            return 'alpha-fast';
        }
    }

    /**
     *更新 货代收货时间 上网 签收（拒签）状态 签收（拒签）时间.
     */
    public function actionUpdateShipmentTime()
    {
        //货代收货时间
        /*
         * $orders = Yii::$app->db->createCommand("select id from orders where shipment_picked_up_date is NULL ")->queryAll();
         * $track_log = new TrackLog();
         * foreach ($orders as $v){
         * $order_t = Yii::$app->db->createCommand("select t.track_date,t.track_status,o.county,o.lc_foreign from orders as o LEFT JOIN track_log as t on t.order_id = o.id WHERE o.id = '{$v['id']}' ORDER BY track_date DESC")->queryAll();
         * foreach ($order_t as $order) {
         * if ($order['county'] == 'TH' && $order['lc_foreign'] == 'kerryexpress') {
         * if (strpos($order['track_status'], 'Delivery Successful') !== false) {
         * $shipment_picked_up_date = $order['track_date'];
         * }
         * } else {
         * if (($track_log->tractStatuss)[$order['county']][$order['lc_foreign']][$order['track_status']] == '货代收货') {
         * $shipment_picked_up_date = $order['track_date'];
         * }
         * }
         * if (isset($shipment_picked_up_date)) {
         * Yii::$app->db->createCommand()->update('orders', ['shipment_picked_up_date' => $shipment_picked_up_date], "id = '{$v['id']}''")->execute();
         * }
         * }
         * }
         */
    }

    /**
     * 更新订单物流状态
     * 签收（拒签）状态 签收（拒签）时间.
     *
     * @throws \yii\db\Exception
     *                           track_status', ['Delivered', 'Exception']
     */
    public function actionUpdateOrderTrackStatus()
    {
        $orders = Yii::$app->db->createCommand("SELECT id FROM orders WHERE track_status NOT IN ('Delivered', 'Exception') AND `status`!='已取消' AND lc_number!='' AND lc_number IS NOT NULL")->queryAll();
//        $orders = Yii::$app->db->createCommand("select id from orders where `status` IN ('拒签') AND lc_foreign = 'post_tw' AND lc = '易速配'")->queryAll();
        $track_log = new TrackLog();
        $logModel = new OrderLogs();
        foreach ($orders as $v) {
            if (isset($status)) {
                unset($status);
            }
            $order_t = Yii::$app->db->createCommand("select t.track_date,t.track_status,o.county,o.lc_foreign from orders as o LEFT JOIN track_log as t on t.order_id = o.id WHERE o.id = '{$v['id']}' ORDER BY track_date DESC ")->queryAll();
//            print_r($order_t);
            foreach ($order_t as $order) {
                if ($order['county'] == 'TH' && $order['lc_foreign'] == 'kerryexpress') {
                    if (strpos($order['track_status'], 'Delivery Successful') !== false) {
                        $status = '已签收';
                        $delivery_date = $order['track_date'];
                        $track_status = $order['track_status'];
                    } else {
                        if ($order['county'] && $order['lc_foreign'] && $order['track_status']) {
                            if (isset(($track_log->tractStatuss)[$order['county']][$order['lc_foreign']][$order['track_status']])) {
                                if (($track_log->tractStatuss)[$order['county']][$order['lc_foreign']][$order['track_status']] == '签收') {
                                    if (isset($status)) {
                                        if ($status == '拒签') {
                                        } else {
                                            $status = '已签收';
                                            $delivery_date = $order['track_date'];
                                            $track_status = $order['track_status'];
                                        }
                                    } else {
                                        $status = '已签收';
                                        $delivery_date = $order['track_date'];
                                        $track_status = $order['track_status'];
                                    }
                                } elseif (($track_log->tractStatuss)[$order['county']][$order['lc_foreign']][$order['track_status']] == '拒签') {
                                    $status = '拒签';
                                    $delivery_date = $order['track_date'];
                                    $track_status = $order['track_status'];
                                } else {
                                }
                            }
                        }
                    }
                } else {
                    if ($order['county'] && $order['lc_foreign'] && $order['track_status']) {
                        if (isset(($track_log->tractStatuss)[$order['county']][$order['lc_foreign']][$order['track_status']])) {
                            if (($track_log->tractStatuss)[$order['county']][$order['lc_foreign']][$order['track_status']] == '签收') {
                                if (isset($status)) {
                                    if ($status == '拒签') {
                                    } else {
                                        $status = '已签收';
                                        $delivery_date = $order['track_date'];
                                        $track_status = $order['track_status'];
                                    }
                                } else {
                                    $status = '已签收';
                                    $delivery_date = $order['track_date'];
                                    $track_status = $order['track_status'];
                                }
                            } elseif (($track_log->tractStatuss)[$order['county']][$order['lc_foreign']][$order['track_status']] == '拒签') {
                                $status = '拒签';
                                $delivery_date = $order['track_date'];
                                $track_status = $order['track_status'];
                            }
                        }
                    }
                }
            }

            if (isset($status)) {
                echo $v['id'].' '.$status.' '.$delivery_date.' '.$track_status."\n";

                $logModel->setIsNewRecord(true);
                unset($logModel->id);
                $logModel->attributes = [
                    'order_id' => $v['id'],
                    'status' => $status,
                    'comment' => date('Y-m-d').'批量更新物流状态为：'.$status,
                    'user_id' => 1,
                ];
                $logModel->save();
                $track_status = $status == '已签收' ? 'Delivered' : 'Exception';

                echo Yii::$app->db->createCommand()->update('orders', ['status' => $status, 'delivery_date' => $delivery_date, 'track_status' => $track_status], "id = '{$v['id']}'")->execute();
            }
        }
        $this->actionGetHl();
    }

    public function actionGetHl()
    {
        $orders = Yii::$app->db->createCommand("SELECT id FROM orders WHERE track_status NOT IN ('Delivered', 'Exception') AND `status`!='已取消' AND lc_number!='合联' AND lc_number IS NOT NULL")->queryAll();
        foreach ($orders as $order) {
            if ($res = Yii::$app->db->createCommand("select * from track_log where order_id = '{$order['id']}' AND remark = 'POD'")->queryOne()) {
                echo Yii::$app->db->createCommand()->update('orders', ['status' => '已签收', 'delivery_date' => $res['track_date'], 'track_status' => 'Delivered'], "id = '{$orders['id']}'")->execute();
            }
        }
    }

    /**
     * 更新上网时间.
     *
     * @throws \yii\db\Exception
     */
    public function actionUpdatePickupTime()
    {
        $orders = Yii::$app->db->createCommand("select id from orders where (pickup_date = '' OR pickup_date IS NULL) AND lc_number!='' AND lc_number IS NOT NULL AND lc!='商壹'")->queryAll();
        foreach ($orders as $v) {
            if (isset($pickup_date)) {
                unset($pickup_date);
            }
            $order_t = Yii::$app->db->createCommand("select t.track_date,t.track_status,o.county,o.lc_foreign from orders as o LEFT JOIN track_log as t on t.order_id = o.id WHERE o.id = '{$v['id']}' AND t.track_date > '2017-01-01' ORDER BY track_date ASC limit 1")->queryOne();
            if ($order_t['track_date']) {
                $pickup_date = $order_t['track_date'];
                $track_status = $order_t['track_status'];
            }

            if (isset($pickup_date)) {
                echo $v['id'].' '.$pickup_date.' '.$track_status."\n";
                echo Yii::$app->db->createCommand()->update('orders', ['pickup_date' => $pickup_date], "id = '{$v['id']}'")->execute();
            }
        }

        $orders = Yii::$app->db->createCommand("select id from orders where (pickup_date = '' OR pickup_date IS NULL) AND lc_number!='' AND lc_number IS NOT NULL AND  lc = '商壹'")->queryAll();
        foreach ($orders as $v) {
            if (isset($pickup_date)) {
                unset($pickup_date);
            }
            $order_t = Yii::$app->db->createCommand("select t.track_date,t.track_status,o.county,o.lc_foreign from orders as o LEFT JOIN track_log as t on t.order_id = o.id WHERE o.id = '{$v['id']}' AND t.track_date > '2017-01-01' and (t.track_status = 'Transferred to 3PL' or t.track_status like ' Arrived%station') ORDER BY t.track_date ASC limit 1")->queryOne();
            if ($order_t['track_date']) {
                $pickup_date = $order_t['track_date'];
                $track_status = $order_t['track_status'];
            }

            if (isset($pickup_date)) {
                echo $v['id'].' '.$pickup_date.' '.$track_status."\n";
                echo Yii::$app->db->createCommand()->update('orders', ['pickup_date' => $pickup_date], "id = '{$v['id']}'")->execute();
            }
        }
    }

    /**
     * 更新发货时间（没有发货时间的）.
     */
    public function actionUpdateShippingdate()
    {
        $orders = Yii::$app->db->createCommand("select id from orders where shipping_date is null or shipping_date = ''")->queryAll();
        foreach ($orders as $v) {
            $order_logs = Yii::$app->db->createCommand("select create_date from order_logs where order_id = '{$v['id']}' AND status = '已发货' ORDER BY create_date desc limit 1")->queryOne();
            if ($order_logs) {
                if ($order_logs['create_date']) {
                    echo $v['id'].' '.$order_logs['create_date'].' ';
                    echo Yii::$app->db->createCommand()->update('orders', ['shipping_date' => $order_logs['create_date']], "id = '{$v['id']}'")->execute();
                    echo "\n";
                }
            }
        }
    }

    /**
     * 目前只支持泰国，马来.
     */
    public function actionGetYundan()
    {
        $orders = Orders::find()->andWhere(['in', 'county', ['MY']])->andWhere(['=', 'status', '已发货'])->all();
        foreach ($orders as $order) {
            $channel_id = $order->getChannelId();
            if (!in_array($channel_id, [371, 370])) {
                echo json_encode(['code' => 500, 'msg' => 'DHL不支持该地区, 货代已更改为商一，请重新称重']);
                exit();
            } else {
                $res = $this->getYundanFromNewApi($order);
                exit;
            }
        }
    }

    /**
     * K1-API获取DHL运单号和物流轨迹文档.
     *
     * @param $id_arr
     * @param $track_type
     */
    public function getYundanFromNewApi($id_arr)
    {
        list($msec, $sec) = explode(' ', microtime());
        $msectime = floor(($sec + $msec) * 1000);
        switch ($id_arr->county) {
            case 'TH':
                $county = 'Thailand';
                $price = $id_arr->price;
                break;
            case 'MY':
                $county = 'Malaysia';
                $price = $id_arr->price;
                break;
            case 'VN':
                $county = 'Vietnam';
                $price = sprintf('%.0f', ($id_arr->price) / 500) * 500;
                break;
            default:
                $county = '';
                $price = $id_arr->price;
                break;
        }
        if ($county) {
            $order_items = OrdersItem::find()->andWhere(['=', 'order_id', $id_arr->id])->all();
            $order_package_wz = OrderPackageWz::find()->andWhere(['=', 'order_id', $id_arr->id])->one();
            $orderDetails = [];
            $product = Products::findOne($id_arr->website);
            foreach ($order_items as $order_item) {
                $orderDetails[] = [
                    'sku' => $order_item->sku,
                    'productname' => $product->declaration_cname,
                    'productenname' => $product->declaration_ename,
                    'price' => sprintf('%.0f', $price / count($order_items)).'',
                    'gcount' => $order_item->qty,
                    'isCharge' => '0',
                    'hsCode' => '7113209090',
                    'currency' => $id_arr->currencyType[$id_arr->county],
                    'unit' => sprintf('%.0f', ($price / count($order_items)) / $order_item->qty).'',
                ];
            }
//            var_dump($orderDetails);die;
            $logisticsChannel = $id_arr->county.'-DHL-COD-';
            $logisticsChannel .= $product->product_type == '普货' ? 'P' : 'M';
            $data1 = [
                'api_key' => '0b8b5f8bdf0f41c49abf84830d1ec407',
                'country' => $county,
                'isCharge' => '0',
                'isCod' => '1',
                'total' => $price,
                'saleNumber' => $id_arr->id,
                'totalWeight' => ($order_package_wz['weight']) * 1000,
                'postCode' => $id_arr->post_code,
                'phone' => $id_arr->mobile,
                'nameto' => $id_arr->name,
                'addressto' => $id_arr->address,
                'cityto' => $id_arr->district,
                'provinceto' => $id_arr->city,
                'orderDetails' => $orderDetails,
                'codValue' => $id_arr->price,
                'logisticsChannel' => $logisticsChannel,     //物流渠道编码
                'businessCode' => 'A10063A',   //商家编码
                'gcount' => $id_arr->qty, //总个数
                'isSensitiv' => $product->product_type == '普货' ? '1' : '2', // 是否敏感
                'isLiquid ' => '1',  //是否液体
                'isPowder' => '1',  //是否是粉末
                'isTaxation' => '1', // 是否免征税
                'currency' => $id_arr->currencyType[$id_arr->county],
                'countryCode' => $id_arr->county,
                'trackingType' => 'DHL',
//                "volume" => $order_package_wz['length'] . '*' . $order_package_wz['width'] . '*' . $order_package_wz['height'] . '*' . '1',
                'volume' => '3*3*3*1',
                'isSaveTms' => '1',
            ];
            $data2 = json_encode($data1);
            $validateCode = md5($msectime.$data2.pack('H*', '7e5f3bb68b6ed9d7b1b0c833b596521e'));

            $post_data = [
                'validateCode' => $validateCode,
                'requestId' => $msectime,
                'data' => $data1,
            ];

            $url = 'http://koneapitest.hanlongljj.cn/v1/Tracking/getTrackingNumber';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
            $output = curl_exec($ch);
            curl_close($ch);
            $output_arr = json_decode($output, true);
            var_dump($output_arr);

            return $output_arr;
        } else {
        }
    }

    public function actionKerryExpress()
    {
        $order = Orders::find()->andWhere(['=', 'lc', '博佳图'])->andWhere('lc_number is not null')->orderBy('create_date desc')->all();
        foreach ($order as $v) {
            (new Corders())->kerryExpress($v->lc_number, $v->id);
        }
//        (new Corders())->kerryExpress('BJTAK1805070002',80043565);
    }

    public function actionSf()
    {
        $order = Orders::find()->andWhere(['=', 'id', '80017653'])->one();
        $order_package = OrderPackageWz::find()->andWhere(['=', 'order_id', $order['id']])->one();
        $product = Products::findOne($order['website']);
        try {
            $client = new \SoapClient('https://bsp-oisp.sf-express.com/bsp-oisp/ws/expressService?wsdl');
//            var_dump($client->__getFunctions());die;
            $request = '<Request service="OrderService" lang="zh-CN">
                            <Head>SZAKXXKJ,UR75gdXgn9HShtgSnkipmpsNo79IzhxS</Head>
                                <Body>
                                    <Order
                                          orderid="'.$order['id'].'"
                                          j_company="Shenzhen Orko Info and technology Co., Ltd"
                                          j_contact="Kevin"
                                          j_tel="985625083@qq.com"
                                          j_mobile="985625083@qq.com"
                                          j_shippercode="CN"
                                          j_country="中国"
                                          j_province="广东省"
                                          j_city="深圳市"
                                          j_county="宝安区"
                                          j_address="Building, Room 535, No. 288 Xixiang Dadao,, Baoan, Shenzhen, China"
                                          j_post_code="518000"
                                          d_company="'.$order['name'].'"
                                          d_contact="'.$order['name'].'"
                                          d_tel="'.$order['mobile'].'"
                                          d_deliverycode="HK"
                                          d_country="HK"
                                          d_province="Hong Kong"
                                          d_city="Hong Kong"
                                          d_county="Hong Kong"
                                          custid="7551234567"
                                          pay_method="1"
                                          express_type="1"
                                          is_gen_bill_no="1"
                                          d_address="'.$order['address'].'"
                                          parcel_quantity="1"
                                          cargo_total_weight="'.sprintf('%.3f', $order_package['weight']).'"
                                          declared_value="'.sprintf('%.3f', $order['price'] - $order['prepayment_amount']).'"
                                          declared_value_currency="HKD">
                                               <Cargo
                                                    name="'.$product['declaration_cname'].'"
                                                    count="1"
                                                    unit="个"
                                                    weight="'.sprintf('%.3f', $order_package['weight']).'"
                                                    amount="'.sprintf('%.3f', $order['price'] - $order['prepayment_amount']).'"
                                                    currency="HKD"
                                                    source_area="HK">
                                                </Cargo>
                                                <AddedService
                                                    name="COD"
                                                    value="'.sprintf('%.3f', $order['price'] - $order['prepayment_amount']).'" >
                                                </AddedService>
                                    </Order>
                                </Body>
                            </Request>';
            $return = $client->sfexpressService(['arg0' => $request]);
            $response = $return->return;
            $response = simplexml_load_string($response);
            if ($response->Head == 'OK') {
                $lc_number_r = $response->Body;
                $lc_number_r = $lc_number_r->OrderResponse;
                $lc_number_r = ($lc_number_r->attributes());
                $lc_number_r = $lc_number_r->mailno;
                $lc_number_r = json_decode(json_encode($lc_number_r), true);
                $lc_number_r = $lc_number_r[0];
                $order->lc_number = $lc_number_r;
                $order->save();
                $pdf = $this->curl_file_get_contents('http://admin.orkotech.com/index/th-single-plane-bjt?id='.$order->id);
                file_put_contents(Yii::$app->getBasePath().'/web/pdf/'.$order->lc_number.'.pdf', $pdf);
                echo json_encode([
                    'code' => 200,
                    'msg' => '保存成功, 货代：博佳图',
                    'order_num' => $order->lc_number,
                    'pdf_url' => 'http://admin.orkotech.com/index/th-single-plane-bjt?id='.$order->id,
                ]);
                $shipping_ok = true;
            } else {
                echo json_encode(['code' => 500, 'msg' => $response->ERROR]);
            }
        } catch (\SoapFault $e) {
            echo json_encode(['code' => 500, 'msg' => '系统错误：'.$e]);
        }
    }
}
