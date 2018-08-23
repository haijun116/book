<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/15
 * Time: 14:34
 */

namespace app\common\components;


use yii\web\Controller;

class BaseWebController extends Controller
{
    public $enableCsrfValidation = false;

    //post
    public function post($key, $default = '')
    {
        return \Yii::$app->request->post($key, $default);
    }

    public function get($key, $default = '')
    {
        return \Yii::$app->request->get($key,$default);
    }


    protected function setCookie($name, $value, $expire = 0)
    {

        $cookies = \Yii::$app->response->cookies;

        $cookies->add(new \yii\web\Cookie([
            'name' => $name,
            'value' => $value,
            'expire' => $expire
        ]));
    }

    protected function getCookie($name, $default_val = '')
    {

        $cookies = \Yii::$app->request->cookies;

        return $cookies->getValue($name, $default_val);
    }

    protected function removeCookie($name, $default_val = '')
    {
        $cookies = \Yii::$app->response->cookies;
        return $cookies->remove($name);
    }

    protected function renderJSON($data = [], $msg = 'ok', $code = '200')
    {

        header('Conten-type:application/json');

        echo json_encode([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
            'req_id' => uniqid()
        ]);
    }


    //统一的js的跳转
    protected  function renderJS($msg,$url = "/"){
        return $this->renderPartial("@app/views/common/js", ['msg' => $msg, 'location' => $url]);
    }

}