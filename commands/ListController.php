<?php
/**
 * Created by PhpStorm.
 * User: lion
 * Date: 2018/8/12
 * Time: 下午6:19
 */

namespace app\commands;


use app\common\services\weixin\TemplateService;
use app\models\QueueList;

class ListController extends BaseController
{


    public function actionRun()
    {
        $list = QueueList::find()->where(['status' => -1])->orderBy(['id' => SORT_ASC])->limit(10)->all();
        if (!$list) {
            return false;
        }

        foreach ($list as $item) {
            $this->echoLog("queue_id:{$item['id']}");
            switch ($item['queue_name']) {
                case 'pay':
                    $this->handlePay($item);
                    break;
            }
            $item->status = 1;
            $item->updated_time = date("Y-m-d H:i:s");
            $item->update( 0 );
        }
        return $this->echoLog("it's over ~~");

    }


    private function handlePay($item)
    {
        $data = @json_decode($item['data'], true);
        if (!$data['member_id'] || !$data['pay_order_id']) {
            return false;
        }
        if (!$data['member_id'] || !$data['pay_order_id']) {
            return false;
        }
        TemplateService::payNotice($data['pay_order_id']);
        return true;
    }
}