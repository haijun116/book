<?php
/**
 * Created by PhpStorm.
 * User: lion
 * Date: 2018/8/12
 * Time: 下午5:58
 */

namespace app\common\services;


use app\models\QueueList;

class QueueListService extends BaseService
{

    public static function addQuene($queue_name,$data = []){

        $model = new QueueList();
        $model->queue_name = $queue_name;
        $model->data = json_encode($data);
        $model->status = -1;
        $model->created_time = $model->updated_time = date("Y-m-d H:i:s");
        $model->save(0);
    }
}