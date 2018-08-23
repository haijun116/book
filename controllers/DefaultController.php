<?php

namespace app\controllers;

use app\common\components\BaseWebController;
use app\common\services\captcha\ValidateCode;
use app\common\services\UrlService;
use app\common\services\UtilService;
use app\common\services\weixin\TemplateService;
use app\models\sms\SmsCaptcha;

class DefaultController extends BaseWebController
{
    private $captcha_cookie_name = 'validate_code';

    public function actionIndex()
    {
        $this->layout = 'main';
        return $this->render('index');
    }


    public function actionImg_captcha()
    {

        $path = \Yii::$app->getBasePath() . '/web/fonts/captcha.ttf';

        $captcha = new ValidateCode($path);

        $captcha->doimg();

        $this->setCookie($this->captcha_cookie_name, $captcha->getCode());
    }


    public function actionGet_captcha()
    {

        $mobile = $this->post('mobile', '');

        $img_captcha = strtolower($this->post('img_captcha', ''));

        if (!$mobile || !preg_match('/^1[0-9]{10}$/', $mobile)) {

            $this->renderJSON([], '请输入合法的手机号码',-1);
        }

        $captcha_code = $this->getCookie($this->captcha_cookie_name);


        if ($img_captcha != $captcha_code) {
            $this->removeCookie($this->captcha_cookie_name);
            $this->renderJSON([],'验证码错误',-1);

        }

        //发送手机验证码

        $sms = new SmsCaptcha();
        $sms->generateCode($mobile,UtilService::getIP());
        $this->removeCookie($this->captcha_cookie_name);
        return $this->renderJSON([],'发送成功，短信验证码为'.$sms->captcha);

    }
}
