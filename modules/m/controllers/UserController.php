<?php
/**
 * Created by PhpStorm.
 * User: lion
 * Date: 2018/7/7
 * Time: 上午9:03
 */

namespace app\modules\m\controllers;


use app\common\services\ConstantMapService;
use app\common\services\UrlService;
use app\common\services\UtilService;
use app\models\book\Book;
use app\models\member\Member;
use app\models\member\MemberComments;
use app\models\member\OauthMemberBind;
use app\models\pay\PayOrder;
use app\models\pay\PayOrderItem;
use app\models\sms\SmsCaptcha;
use app\modules\m\controllers\common\BaseController;
use http\Url;
use PHPUnit\Util\Type;

class UserController extends BaseController
{

    public function actionIndex()
    {

        return $this->render('index', [
            'current_user' => $this->current_user
        ]);
    }


    public function actionBind()
    {

        if (\Yii::$app->request->isGet) {

            return $this->render('bind');
        }

        $mobile = $this->post('mobile', '');
        $img_captcha = $this->post('img_captcha', '');
        $captcha_code = $this->post('captcha_code', '');
        $openid = $this->getCookie($this->openid);


        if (mb_strlen($mobile, 'utf-8') < 1) {

            return $this->renderJSON([], '请输入合法的手机号码', -1);
        }

        if (mb_strlen($img_captcha, 'utf-8') < 1) {

            return $this->renderJSON([], '请输入合法的校验码', -1);
        }

        if (mb_strlen($captcha_code, 'utf-8') < 1) {

            return $this->renderJSON([], '请输入合法手机校验码', -1);
        }


        if (!SmsCaptcha::checkCode($mobile, $captcha_code)) {

            return $this->renderJSON([], '手机校验码错误', -1);
        }


        $member_info = Member::find()->where(['mobile' => $mobile, 'status' => 1])->one();

        if (!$member_info) {

            if (Member::findOne(['mobile' => $mobile])) {
                return $this->renderJSON([], '手机号码已经注册，请直接使用手机号码登录', -1);
            }

            $model_member = new Member();
            $model_member->nickname = $mobile;
            $model_member->avatar = ConstantMapService::$default_avatar;
            $model_member->mobile = $mobile;
            $model_member->setSalt();
            $model_member->reg_ip = ip2long(UtilService::getIP());
            $model_member->status = 1;
            $model_member->created_time = date('Y-m-d H:i:s');
            $model_member->updated_time = date('Y-m-d H:i:s');
            $model_member->save(0);
            $member_info = $model_member;

        }

        if (!$member_info || !$member_info['status']) {
            return $this->renderJSON([], '该账号已经被禁用', -1);

        }
        //todo 设置登录状态
        if ($openid) {
            $bind_info = OauthMemberBind::findOne(['member_id' => $member_info['id'], "openid" => $openid, 'type' => ConstantMapService::$client_type_wechat]);
            if (!$bind_info) {
                $model_bind = new OauthMemberBind();
                $model_bind->member_id = $member_info['id'];
                $model_bind->type = ConstantMapService::$client_type_wechat;
                $model_bind->client_type = 'weichat';
                $model_bind->openid = $openid ?: '';
                $model_bind->unionid = '';
                $model_bind->extra = '';
                $model_bind->updated_time = date('Y-m-d H:i:s');
                $model_bind->created_time = date('Y-m-d H:i:s');
                $model_bind->save(0);
            }
        }

        if (UtilService::isWechat() && $member_info['nickname'] == $member_info['mobile']) {

            return $this->renderJSON(['url' => UrlService::buildMUrl('/oauth/login', ['scope' => 'snsapi_userinfo'])], '绑定成功');


        }
        $this->setLoginStatus($member_info);
        return $this->renderJSON(['url' => UrlService::buildMUrl('/default/index')], '绑定成功');
    }

    public function actionOrder()
    {
        $pay_order_list = PayOrder::find()->where(['member_id' => $this->current_user['id']])
            ->orderBy(['id' => SORT_DESC])->asArray()->all();

        $list = [];

        if ($pay_order_list) {
            $pay_order_items_list = PayOrderItem::find()->where(['member_id' => $this->current_user['id']])
                ->orderBy(['id' => SORT_DESC])->asArray()->all();
            $book_mapping = Book::find()->where(['id' => array_column($pay_order_items_list, 'target_id')])->indexBy('id')->all();
            $pay_order_items_mapping = [];
            foreach ($pay_order_items_list as $_pay_order_item) {
                $tmp_book_info = $book_mapping[$_pay_order_item['target_id']];
                if (!isset($pay_order_items_mapping[$_pay_order_item['pay_order_id']])) {
                    $pay_order_items_mapping[$_pay_order_item['pay_order_id']] = [];
                }
                $pay_order_items_mapping[$_pay_order_item['pay_order_id']][] = [
                    'pay_price' => $_pay_order_item['price'],
                    'book_name' => UtilService::encode($tmp_book_info['name']),
                    'book_main_image' => UrlService::buildPicUrl("book", $tmp_book_info['main_image']),
                    'book_id' => $_pay_order_item['target_id'],
                    'comment_status' => $_pay_order_item['comment_status']
                ];

            }
            foreach ($pay_order_list as $_pay_order_info) {
                $list[] = [
                    'id' => $_pay_order_info['id'],
                    'sn' => date("Ymd", strtotime($_pay_order_info['created_time'])) . $_pay_order_info['id'],
                    'created_time' => date("Y-m-d H:i", strtotime($_pay_order_info['created_time'])),
                    'pay_order_id' => $_pay_order_info['id'],
                    'pay_price' => $_pay_order_info['pay_price'],
                    'items' => $pay_order_items_mapping[$_pay_order_info['id']],
                    'status' => $_pay_order_info['status'],
                    'express_status' => $_pay_order_info['express_status'],
                    'express_info' => $_pay_order_info['express_info'],
                    'express_status_desc' => ConstantMapService::$express_status_mapping_for_member[$_pay_order_info['express_status']],
                    'status_desc' => ConstantMapService::$pay_status_mapping[$_pay_order_info['status']],
                    'pay_url' => UrlService::buildMUrl("/pay/buy/?pay_order_id={$_pay_order_info['id']}")
                ];
            }


        }

        //var_dump($pay_order_items_mapping);


        return $this->render('order', [
            'list' => $list
        ]);

    }

    public function actionComment()
    {
        $list = MemberComments::find()->where(['member_id' => $this->current_user['id']])
            ->orderBy(['id' => SORT_DESC])->asArray()->all();

        return $this->render('comment', [
            'list' => $list
        ]);
    }

    public function actionComment_set()
    {

        if (\Yii::$app->request->isGet) {

            $pay_order_id = intval($this->get('pay_order_id', 0));
            $book_id = intval($this->get('book_id'), 0);
            $pay_order_info = PayOrder::findOne(['id' => $pay_order_id, 'status' => 1, 'express_status' => 1]);
            $reback_url = UrlService::buildMUrl("/user/index");
            if (!$pay_order_info) {
                return $this->redirect($reback_url);
            }
            $pay_order_item_info = PayOrderItem::findOne(['pay_order_id' => $pay_order_id, 'target_id' => $book_id]);
            if (!$pay_order_item_info) {
                return $this->renderJSON([], '通用错误', -1);
            }
            if ($pay_order_item_info['comment_status']) {
                return $this->renderJS("您已经评论过啦，不能重复评论~~", $reback_url);
            }
            return $this->render('comment_set', [
                'pay_order_info' => $pay_order_info,
                'book_id' => $book_id
            ]);
        }
        $pay_order_id = intval($this->post("pay_order_id", 0));
        $book_id = intval($this->post("book_id", 0));
        $score = intval($this->post("score", 0));
        $content = trim($this->post('content', ''));
        $date_now = date("Y-m-d H:i:s");

        if ($score <= 0) {
            return $this->renderJSON([], "请打分~~", -1);
        }

        if (mb_strlen($content, "utf-8") < 3) {
            return $this->renderJSON([], "请输入符合要求的评论内容~~", -1);
        }
        $pay_order_info = PayOrder::findOne(['id' => $pay_order_id, 'status' => 1, 'express_status' => 1]);

        if (!$pay_order_info) {
            return $this->renderJSON([], '通用错误', -1);
        }
        $pay_order_item_info = PayOrderItem::findOne(['pay_order_id' => $pay_order_id, 'target_id' => $book_id]);

        if (!$pay_order_item_info) {
            return $this->renderJSON([], '通用错误', -1);

        }

        if ($pay_order_item_info['comment_status']) {
            return $this->renderJSON([], "您已经评论过啦，不能重复评论~~", -1);
        }

        $book_info = Book::findOne(['id' => $book_id]);
        if (!$book_info) {
            return $this->renderJSON([], '通用错误', -1);

        }

        $model_comment = new MemberComments();
        $model_comment->member_id = $this->current_user['id'];
        $model_comment->book_id = $book_id;
        $model_comment->pay_order_id = $pay_order_id;
        $model_comment->score = $score * 2;;
        $model_comment->content = $content;
        $model_comment->created_time = $date_now;
        $model_comment->save(0);

        $pay_order_item_info->comment_status = 1;
        $pay_order_item_info->update(0);

        $book_info->comment_count += 1;
        $book_info->update(0);

        return $this->renderJSON([], "评论成功~~");

    }


}