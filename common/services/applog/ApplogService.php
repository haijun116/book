<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/16
 * Time: 14:40
 */

namespace app\common\services\applog;


use app\common\services\UtilService;
use app\models\log\AppAccessLog;
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

    //记录用户访问信息
    public static function addAppAccessLog($uid = 0)
    {

        $get_params = \Yii::$app->request->get();
        $post_params = \Yii::$app->request->post();
        $target_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $access_log = new AppAccessLog();
        $access_log->uid = $uid;
        $access_log->referer_url = $referer;
        $access_log->target_url = $target_url;
        $access_log->query_params = json_encode(array_merge($get_params, $post_params));
        $access_log->ua = $ua;
        $access_log->ip = UtilService::getIP();
        $access_log->created_time = date('Y-m-d H:i:s');
        return $access_log->save(0);
    }
}