<?php
/**
 * Created by PhpStorm.
 * User: lion
 * Date: 2018/8/9
 * Time: 下午8:35
 */

namespace app\commands;


use app\common\services\PayOrderService;
use app\models\pay\PayOrder;

class PayController extends BaseController
{

    public function actionProduct_stock()
    {

        $before_half_date = date('Y-m-d H:i:s');
        $before_half_order_list = PayOrder::find()
            ->where(['target_type' => 1, 'status' => -8])
            ->andWhere(['<=', 'created_time', $before_half_date])
            ->all();

        if (!$before_half_order_list) {
            $this->echoLog('no data');
        }

        foreach ($before_half_order_list as $_order_info) {
            PayOrderService::closeOrder($_order_info['id']);
        }
        return $this->echoLog("it's over ~~");
    }
}