<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "wms_pick_round".  拣货单
 *
 * @property integer $id
 * @property string $ref
 * @property string $create_time
 * @property integer $warehouse_id
 * @property string $location
 * @property integer $print_time
 * @property integer $num
 * @property integer $status
 */
class WmsPickRound extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wms_pick_round';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ref', 'create_time', 'warehouse_id', 'location', 'print_time', 'num', 'status'], 'required'],
            [['create_time'], 'safe'],
            [['warehouse_id', 'print_time', 'num', 'status'], 'integer'],
            [['ref', 'location'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ref' => '单号 波次',
            'create_time' => '生成时间 ',
            'warehouse_id' => '仓库',
            'location' => '库区',
            'print_time' => '打印次数',
            'num' => '订单量',
            'status' => '状态 0：新建 1：拣货中 2：完成',
        ];
    }
}
