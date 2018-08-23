<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "queue_list".
 *
 * @property int $id
 * @property string $queue_name 队列名字
 * @property string $data 队列数据
 * @property int $status 状态 -1 待处理 1 已处理
 * @property string $updated_time 最后一次更新时间
 * @property string $created_time 插入时间
 */
class QueueList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'queue_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['updated_time', 'created_time'], 'safe'],
            [['queue_name'], 'string', 'max' => 30],
            [['data'], 'string', 'max' => 500],
            [['status'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'queue_name' => 'Queue Name',
            'data' => 'Data',
            'status' => 'Status',
            'updated_time' => 'Updated Time',
            'created_time' => 'Created Time',
        ];
    }
}
