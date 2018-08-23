<?php

namespace app\controllers;

use app\common\components\BaseWebController;
use app\common\services\applog\ApplogService;
use Yii;
use yii\log\FileTarget;

class ErrorController extends BaseWebController
{
    public function actionError(){
        //$this->layout = false;
        $error = Yii::$app->errorHandler->exception;
        if($error){
            $file = $error->getFile();
            $code = $error->getCode();
            $message = $error->getMessage();
            $line = $error->getLine();
            $log = new FileTarget();
            $log->logFile = \Yii::$app->runtimePath .'/logs/err.log';
            $err_msg = $message.$code.' [line] '.$line.$file;
            $log->messages[] = [
                $err_msg,
                1,
                'application',
                microtime(true)
            ];
            $log->export();
            ApplogService::addErrorLog(\Yii::$app->id,$err_msg);
        }
        return $this->render('error',['err_msg'=>$err_msg]);
    }
}
