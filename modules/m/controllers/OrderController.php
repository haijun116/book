<?php
/**
 * Created by PhpStorm.
 * User: lion
 * Date: 2018/8/8
 * Time: 下午8:01
 */

namespace app\modules\m\controllers;


use app\common\services\ConstantMapService;
use app\common\services\PayOrderService;
use app\models\pay\PayOrder;
use app\modules\m\controllers\common\BaseController;

class OrderController extends BaseController
{

    public function actionOps()
    {

        if (!\Yii::$app->request->isPost) {
            return $this->renderJSON([], '通用错误1', -1);
        }
        $act = $this->post("act", "");
        $id = intval($this->post("id", 0));
        $date_now = date("Y-m-d H:i:s");

        if (!in_array($act, ["close", "confirm_express"])) {
            return $this->renderJSON([], '通用错误2', -1);
        }
        if (!$id) {
            return $this->renderJSON([], '通用错误3', -1);
        }

        $pay_order_info = PayOrder::find()->where(['id' => $id, 'member_id' => $this->current_user['id']])->one();

        if (!$pay_order_info) {
            return $this->renderJSON([], '通用错误4', -1);
        }

        switch ($act) {
            case 'close':
                if ($pay_order_info['status'] == -8) {
                    PayOrderService::closeOrder($pay_order_info['id']);
                }
        }

        return $this->renderJSON([],"操作成功~~");
    }
}