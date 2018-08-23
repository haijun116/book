<?php

namespace app\modules\web\controllers;

use app\common\services\UrlService;
use app\models\User;
use app\modules\web\controllers\common\BaseController;

/**
 * Default controller for the `web` module
 */
class UserController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */

    public function actionLogin()
    {
        if (\Yii::$app->request->isGet) {
            $this->layout = 'user';
            return $this->render('login');
        }
        $login_name = trim($this->post('login_name', ''));
        $login_pwd = trim($this->post('login_pwd', ''));
        if (!$login_pwd || !$login_name) {
            return $this->renderJs('请输入正确的用户名和密码', UrlService::buildWebUrl('/user/login'));
        }
        //从用户表中查找用户名是否存在
        $user_info = User::find()->where(['login_name' => $login_name])->one();
        if (!$user_info) {
            return $this->renderJs('请输入正确的用户名和密码', UrlService::buildWebUrl('/user/login'));
        }
        //密码加密 md5(login_pwd+ md5(login_salt))

        $auth_pwd = md5($login_pwd . md5($user_info['login_salt']));

        if ($auth_pwd != $user_info['login_pwd']) {
            return $this->renderJs('请输入正确的用户名和密码', UrlService::buildWebUrl('/user/login'));
        }
        //登录 使用 加密字符串 + '#' +uid  加密字符串md5(login_name + login_pwd + login_salt)
        $this->setLoginStatus($user_info);
        return $this->redirect('/web/dashboard/index');
    }

    public function actionEdit()
    {
        if (\Yii::$app->request->isGet) {
            return $this->render('edit', ['user_info' => $this->current_user]);
        }

        $nickname = trim($this->post('nickname', ''));
        $email = trim($this->post('email', ''));
        if (mb_strlen($nickname, 'utf-8') < 1) {
            return $this->renderJSON([], '请输入合法的域名', -1);
        }
        if (mb_strlen($email, 'utf-8') < 1) {
            return $this->renderJSON([], '亲输入合法的邮箱', -1);
        }
        $user_info = $this->current_user;
        $user_info->nickname = $nickname;
        $user_info->email = $email;
        $user_info->updated_time = date('Y-m-d H:i:s');
        $user_info->update(0);
        return $this->renderJSON([],'编辑成功');
    }

    public function actionResetPwd()
    {
        if(\Yii::$app->request->isGet){
            return $this->render('reset_pwd',['user_info'=>$this->current_user]);
        }
        $old_password = trim($this->post('old_password'.''));
        $new_password = trim($this->post('new_password'.''));
        if(mb_strlen($old_password,'utf-8')< 1){
            return $this->renderJSON([],'请输入原密码',-1);
        }
        if(mb_strlen($new_password,'utf-8')<6){
            return $this->renderJSON([],'请输入不少于6位的原密码',-1);
        }
        if($old_password == $new_password){
            return $this->renderJSON([],'请重新输入，新密码和老密码相同',-1);
        }
        //判断原密码是否正确
        $user_info = $this->current_user;
        if(!$user_info->verifyPassword($old_password)){
            $this->renderJSON([],'请检查原密码是否正确',-1);
        }
        //$user_info->login_pwd = md5($new_password.md5($user_info['login_salt']));
        $user_info->login_pwd = $user_info->getSaltPassword($new_password);
        $user_info->updated_time = date('Y-m-d H:i:s');
        $user_info->update(0);
        $this->setLoginStatus($user_info);
        return $this->renderJSON([],'密码更新成功');
    }

    public function actionLogout()
    {
        $this->removeCookie($this->auth_cookie_name);
        return $this->redirect(UrlService::buildWebUrl('/user/login'));
    }
}
