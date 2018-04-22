<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/16
 * Time: 14:40
 */

namespace app\common\services\applog;


use app\common\services\UtilService;
use app\models\log\AppLog;

class ApplogService
{
    public static function addErrorLog($appname, $content)
    {

        $error = \Yii::$app->errorHandler->exception;
        $model_app_logs = new AppLog();
        $model_app_logs->app_name = $appname;
        $model_app_logs->content = $content;

        $model_app_logs->ip = UtilService::getIP();
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            $model_app_logs->ua = $_SERVER['HTTP_USER_AGENT'];
        }

        if ($error) {

            if (method_exists($error, 'getName')) {
                $model_app_logs->err_name = $error->getName();

            }

            if (isset($error->statusCode)) {
                $model_app_logs->http_code = $error->statusCode;
            }

            $model_app_logs->err_code = $error->getCode();
        }
        $model_app_logs->created_time = date("Y-m-d H:i:s");
        $model_app_logs->save(0);
    }
}