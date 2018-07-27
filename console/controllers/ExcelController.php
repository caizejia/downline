<?php
/**
 * Created by PhpStorm.
 * User: 秦洋
 * Date: 2018/3/1
 * Time: 10:48
 */

namespace console\controllers;


use yii\console\Controller;
use Yii;
use common\models\UploadForm;
use PHPExcel;
use common\models\ExcelExport;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ExcelController extends Controller
{

    /**
     * 导出通用
     */
    public function actionExport()
    {
        if (date('i') > 29 && date('i') < 31 && date('H') > 1 && date('H') < 3) {
            $this->actionNicoleOne();
        }
        $excels = Yii::$app->db->createCommand("select * from excel_export where status='待执行'")->queryAll();
        $excel_export = new Excel();
        foreach ($excels as $excel) {
//            echo $excel['method']."\n";
            $method = $excel['method'];
            $request = $excel['request'];
            $user_id = $excel['user_id'];
            $id = $excel['id'];
            $result_name = $excel['result_name'];
            if ($result_name) {
                $res = $excel_export->$method($result_name, $user_id, $method, $id);
                if ($res) {
                    Yii::$app->db->createCommand()->update('excel_export', ['status' => '执行完成', 'end_time' => date('Y-m-d H:i:s'),], "id = {$id}")->execute();
                    Yii::$app->db->createCommand()->insert("message", [
                        'user_id' => $user_id,
                        'title' => $res[0] . '导入完成',
                        'create_user' => '2',
                        'time' => date('Y-m-d H:i:s', time() + 7 * 24 * 3600),
                        'message' => '<a href="/' . $result_name . '" target="_blank" class="btn btn-info">点击下载查看导入文件</a>',
                    ])->execute();
                }
            } else {
                $res = $excel_export->$method($request, $user_id, $method, $id);
                if ($res) {
                    Yii::$app->db->createCommand()->update('excel_export', ['status' => '执行完成', 'end_time' => date('Y-m-d H:i:s'), 'result_name' => $res[0]], "id = {$id}")->execute();
                    Yii::$app->db->createCommand()->insert("message", [
                        'user_id' => $user_id,
                        'title' => $res[1] . '导出完成',
                        'create_user' => '2',
                        'time' => date('Y-m-d H:i:s', time() + 7 * 24 * 3600),
                        'message' => '<a href="/export/' . $res[0] . '" target="_blank" class="btn btn-info">点击下载</a>',
                    ])->execute();
                    if ($user_id == 128 || $user_id == 120 || $user_id == 189) {
                        $this->actionNicoleOne();
                    }
                }
            }
        }
    }


    public function actionUpload()
    {
        $excels = Yii::$app->db->createCommand("select * from wms_uploads where is_use = 0 or use_result != 1")->queryAll();
        $excel_export = new ExcelExport();
        foreach ($excels as $excel) {
            $file_name = $excel['file_name'];
            $filePath = 'web/uploads/' . $file_name; // 要读取的文件的路径
            $data = \moonland\phpexcel\Excel::import($filePath, [
                'setFirstRecordAsKeys' => true,
                'setIndexSheetByName' => true,
                'getOnlySheet' => 'sheet1',
            ]);
            $json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $datas = json_decode($json);
            $purpose = $excel['purpose'];
            $user_id = $excel['user_id'];
            $id = $excel['id'];
            $res = $excel_export->$purpose($datas, $user_id, $purpose, $id);
            if ($res) {
                Yii::$app->db->createCommand()->update('wms_uploads', [
                    'use_time' => date('Y-m-d H:i:s'),
                ], "id = {$id}")->execute();
                Yii::$app->db->createCommand()->insert("message", [
                    'user_id' => $user_id,
                    'title' => $res[0] . '导入完成',
                    'create_user' => '2',
                    'time' => date('Y-m-d H:i:s', time() + 7 * 24 * 3600),
                    'message' => '<a href="/' . $result_name . '" target="_blank" class="btn btn-info">点击下载查看导入文件</a>',
                ])->execute();
            } else {
                Yii::$app->db->createCommand()->update('wms_uploads', [
                    'use_time' => date('Y-m-d H:i:s'),
                    'use_result' => 2,
                ], "id = {$id}")->execute();
            }
        }
    }
}
