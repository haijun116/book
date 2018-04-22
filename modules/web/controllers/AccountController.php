<?php
/**
 * Class UserController
 */

namespace app\modules\web\controllers;


use app\common\services\ConstantMapService;
use app\models\User;
use app\modules\web\controllers\common\BaseController;

class AccountController extends BaseController
{

    public function actionIndex()
    {
        $status = intval($this->get('status', ConstantMapService::$status_default));
        $mix_kw = trim($this->get('mix_kw', ''));
        $p = trim($this->get('p', 1));
        $query = User::find();
        if ($status > ConstantMapService::$status_default) {
            $query->andWhere(['status' => $status]);
        }
        if ($mix_kw) {
            $where_nickname = ['like', 'nickname', '%' . $mix_kw . '%', false];
            $where_mobile = ['like', 'mobile', '%' . $mix_kw . '%', false];
            $query->andWhere(['OR', $where_nickname, $where_mobile]);
        }
        //分页，首先获取总页数，然后获取总页数的条数
        $page_size = 10; //每页显示的条数
        $total_count = $query->count();//总记录数
        $total_page = ceil($total_count / $page_size);
        $list = $query->orderBy(['uid' => SORT_DESC])
            ->offset(($p - 1) * $page_size)
            ->limit($page_size)
            ->all();

        return $this->render('index', [
            'list' => $list,
            'status_mapping' => ConstantMapService::$status_mapping,
            'search_conditions' => [
                'mix_kw' => $mix_kw,
                'status' => $status,
                'p' => $p
            ],
            'pages' => [
                'total_count' => $total_count,
                'page_size' => $page_size,
                'total_page' => $total_page,
                'p' => $p
            ],
        ]);
    }

    public function actionSet()
    {
        $this->layout = 'main';
        return $this->render('set');
    }

    public function actionInfo()
    {
        $this->layout = 'main';
        return $this->render('info');
    }

    //操作方法
    public function actionOps()
    {
        if (!\Yii::$app->request->isPost) {
            return $this->renderJSON([], '系统繁忙，请稍后重试');
        }
        $uid = intval($this->post('uid', 0));
        $act = trim($this->post('act', ''));

        if (!$uid) {
            return $this->renderJSON([], '请选择操作的账号', -1);
        }
        if (!in_array($act, ['remove', 'recove'])) {
            return $this->renderJSON([], '操作有误，请重试', -1);
        }
        $user_info = User::find()->where(['uid' => $uid])->one();
        if (!$user_info) {
            return $this->renderJSON([], '指定的账户不存在', -1);
        }

        switch ($act) {
            case 'remove':
                $user_info->status = 0;
                break;
            case 'recove':
                $user_info->status = 1;
                break;
        }
        $user_info->updated_time = date("Y-m-d H:i:s");
        $user_info->update(0);
        return $this->renderJSON([], '操作成功');
    }
}