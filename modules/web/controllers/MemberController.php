<?php
/**
 * Class UserController
 */

namespace app\modules\web\controllers;


use app\common\services\ConstantMapService;
use app\common\services\UrlService;
use app\common\services\UtilService;
use app\models\log\AppAccessLog;
use app\models\member\Member;
use app\models\User;
use app\modules\web\controllers\common\BaseController;
use yii\web\Controller;

class MemberController extends BaseController
{

    public function actionIndex()
    {
        $mix_kw = trim($this->get('mix_kw', ''));
        $status = trim($this->get('status', ConstantMapService::$status_default));
        $p = intval(($this->get('p', 1)));
        $p = ($p > 0) ? $p : 1;
        $query = Member::find();
        if ($mix_kw) {
            $where_nickname = ['like', 'nickname', '%' . strtr($mix_kw, ['%' => '\%', '_' => '\_']) . '%', false];
            $where_mobile = ['like', 'mobile', '%' . strtr($mix_kw, ['%' => '\%', '_' => '\_']) . '%', false];
            $query->andWhere('or', $where_nickname, $where_mobile);
        }
        if ($status > ConstantMapService::$status_default) {
            $query->andWhere(['status' => $status]);
        }

        //分页功能，1，符合条件的总记录数,2 每页显示的条数
        $page_size = 10;
        $total_res_count = $query->count();
        $total_page = ceil($total_res_count / $page_size);
        $list = $query->orderBy(['id' => SORT_DESC])
            ->offset(($p - 1) * $page_size)
            ->limit($page_size)
            ->all();
//
        $data = [];
        if ($list) {
            foreach ($list as $item) {
                $data[] = [
                    'id' => $item['id'],
                    'nickname' => UtilService::encode($item['nickname']),
                    'mobile' => UtilService::encode($item['mobile']),
                    'sex_desc' => ConstantMapService::$sex_mapping[$item['sex']],
                    'avatar' => UrlService::buildPicUrl('avatar', $item['avatar']),
                    'status_desc' => ConstantMapService::$status_mapping[$item['status']],
                    'status' => $item['status']
                ];
            }
        }
        return $this->render('index', [
            'list' => $data,
            'search_condition' => [
                'mix_kw' => $mix_kw,
                'p' => $p,
                'status' => $status
            ],
            'status_mapping' => ConstantMapService::$status_mapping,
            'pages' => [
                'total_count' => $total_res_count,
                'page_size' => $page_size,
                'total_page' => $total_page,
                'p' => $p
            ]
        ]);

    }

    public function actionSet()
    {

        if (\Yii::$app->request->isGet) {
            $id = intval($this->get('id', 0));
            $info = [];
            if ($id) {
                $info = Member::find()->where(['id' => $id])->one();
            }

            return $this->render('set', [
                'info' => $info
            ]);
        }

        $id = intval($this->post('id', 0));
        $nickname = trim($this->post('nickname', ''));
        $mobile = floatval($this->post('mobile', ''));
        $date_now = date('Y-m-d H:i:s');
        if (mb_strlen($nickname, 'utf-8') < 1) {
            return $this->renderJSON([], '请输入符合规范的姓名', -1);

        }
        if (mb_strlen($mobile, 'utf-8') < 1) {
            return $this->renderJSON([], '请输入符合规范的手机号码', -1);
        }
        $info = [];
        if ($id) {
            $info = Member::findOne(['id' => $id]);
        }
        if ($info) {
            $model_member = $info;
        } else {
            $model_member = new Member();
            $model_member->status = 1;
            $model_member->avatar = ConstantMapService::$default_avatar;
            $model_member->created_time = $date_now;
        }

        $model_member->nickname = $nickname;
        $model_member->mobile = $mobile;
        $model_member->updated_time = $date_now;
        $model_member->save(0);
        return $this->renderJSON([], '操作成功');

    }

    public function actionInfo()
    {
        $id = intval($this->get('id',0));
        $reback_url = UrlService::buildWebUrl('/member/index');
        if(!$id){
            return $this->redirect($reback_url);
        }
        $info = Member::find()->where(['id'=>$id])->one();
        if(!$info){
            return $this->redirect($reback_url);
        }
        return $this->render('info',[
            'info'=>$info
        ]);
    }

    public function actionComment()
    {

        return $this->render('comment');
    }

    public function actionOps(){
        if(!\Yii::$app->request->isPost){
            return $this->renderJSON([],'非法请求',-1);
        }
        $id = $this->post('id',[]);
        $act = trim($this->post('act',''));
        if(!$id){
            return $this->renderJSON([],'请选择要操作的会员号',-1);
        }
        if(!in_array($act,['remove','recove'])){
            return $this->renderJSON([],'操作有误，请重试',-1);
        }
        $info = Member::find()->where(['id'=>$id])->one();
        if(!$info){
            return $this->renderJSON([],'指定的会员账号不存在',-1);
        }
        switch ($act){
            case 'remove':
                $info->status = 0;
                break;
            case 'recove':
                $info->status = 1;
                break;
        }
        $info->updated_time = date('Y-m-d H:i:s');
        $info->update(0);
        return $this->renderJSON([],'操作成功');
    }
}