<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/3
 * Time: 13:39
 */

namespace app\modules\weixin\controllers;


use app\common\components\BaseWebController;

class MsgController extends BaseWebController
{
    public function actionIndex(){

        if(!$this->checkSignature()){
            return 'error signature';
        }
        if(array_key_exists('echostr',$_GET) && $_GET['echostr']){
            echo  $_GET['echostr'];
        }
    }

    public function checkSignature(){
        $signature = trim($this->get('signature'));
        $timestamp = trim($this->get('timestamp'));
        $nonce = trim($this->get('nonce'));
        $tmpArr = array(\Yii::$app->params['weixin']['token'],$timestamp,$nonce);
        sort($tmpArr);
        $tmpArr = implode($tmpArr);
        $tmpArr = sha1($tmpArr);
        if($tmpArr == $signature){
            return true;
        }else{
            return false;
        }
    }
}