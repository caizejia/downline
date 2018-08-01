<?php

namespace api\modules\v1\oms\controllers;

use Yii;
use yii\web\Controller;
use mdm\admin\components\Helper;
use api\components\logistics\Logistics;
use api\components\logistics\AFLStrategy;
use api\components\logistics\OrkoKerryStrategy;
use api\models\User;

/**
 * Default controller for the `v1` module.
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionTest()
    {
        $sql = 'SELECT id FROM user WHERE status = 10';
        $c = Yii::$app->db->createCommand($sql);
        $data = $c->queryAll();
        $time = time();
        foreach ($data as &$v) {
            $v['item_name'] = '任务模块临时角色';
            $v['created_at'] = $time;
        }
        Yii::$app->db->createCommand()->batchInsert(
            'auth_assignment',
            [
                'user_id', 'item_name','created_at' 
            ],$data)->execute();
        // 2018/07/19
        //$sql = 'SELECT * FROM admin_user';
        //$c = Yii::$app->db->createCommand($sql);
        //$data = $c->queryAll();
        //$time = time();
        //foreach ($data as &$v) {
        //    $v['realname'] = $v['name'];
        //    $v['dingding_id'] = $v['ding_id'] ?? 0;
        //    $v['created_at'] = $v['created_at'] ?? time();
        //    $v['updated_at'] = $v['updated_at'] ?? time();
        //    $v['website_id'] = $v['website_id'] ?? 0;
        //    $v['warehouse_id'] = $v['w_id'];
        //    $v['access_token'] = uniqid();
        //    unset($v['ding_id'], $v['name'], $v['w_id']);
        //}
        //Yii::$app->db->createCommand()->batchInsert(
        //    'user', 
        //    [
        //        'id', 'username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'status', 
        //        'created_at', 'updated_at', 'website_id', 'is_super_user', 'group_id', 
        //        'is_leader', 'service_area', 'classification',  'realname', 'dingding_id', 'warehouse_id','access_token'    
        //    ], $data)->execute();
        // end
        //
        //$help = new Helper();
        //$routeList = array_keys($help->getRoutesByUser(1));
        //var_dump($routeList);exit;
        // 2018/07/15
        //$logistics = new Logistics(new OrkoKerryStrategy());
        //$data = $logistics->pushOrder(1);
        //var_dump($data);exit;
        // end
        

    }

}
