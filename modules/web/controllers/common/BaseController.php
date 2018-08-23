<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/15
 * Time: 20:18
 */

namespace app\modules\web\controllers\common;


use app\common\components\BaseWebController;
use app\common\services\applog\ApplogService;
use app\common\services\UrlService;
use app\models\User;

class BaseController extends BaseWebController
{

    protected  $auth_cookie_name = "mooc_book";
    protected $current_user = null;
    public $allowAction = [
        'web/user/login'
    ];
    public function __construct($id, $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->layout = 'main';
    }

    public function beforeAction($action)
    {
        $is_login = $this->checkLoginStatus();
        if(in_array($action->getUniqueId(),$this->allowAction)){
            return true;
        }
        if(!$is_login){
            if(\Yii::$app->request->isAjax){
                $this->renderJSON([],'未登录，请先登录',-302);
            }else{
                $this->redirect(UrlService::buildWebUrl('/user/login'));
            }
            return false;
        }

        //记录所以记录的访问
        ApplogService::addAppAccessLog($this->current_user['uid']);

        return true;
    }

    //登录状态的验证
    private function checkLoginStatus()
    {
        $auth_cookie = $this->getCookie($this->auth_cookie_name, '');
        if (!$auth_cookie) {
            return false;
        }
        list($auth_token, $uid) = explode('#', $auth_cookie);
        if (!$auth_token || !$uid) {
            return false;
        }
        if (!preg_match('/^\d+$/', $uid)) {
            return false;
        }

        $user_info = User::find()->where(['uid' => $uid])->one();
        $this->current_user = $user_info;
        if (!$user_info) {
            return false;
        }
        $auth_token_md5 = md5($user_info['login_name'] . $user_info['login_pwd'] . $user_info['login_salt']);
        if ($auth_token != $auth_token_md5) {
            return false;
        }

        return true;
    }

    public function setLoginStatus($user_info){
        $auth_token = $this->geneAuthToken($user_info);
        $this->setCookie($this->auth_cookie_name,$auth_token.'#'.$user_info['uid']);
    }

    public function geneAuthToken($user_info){
        return md5($user_info['login_name']. $user_info['login_pwd'].$user_info['login_salt']);
    }

    public function removeAuthToken(){
        $this->removeCookie($this->auth_cookie_name);
    }
}