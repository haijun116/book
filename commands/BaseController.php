<?php
/**
 * Created by PhpStorm.
 * User: lion
 * Date: 2018/8/9
 * Time: 下午8:40
 */

namespace app\commands;


use yii\console\Controller;

class BaseController extends Controller
{

    public function echoLog($msg)
    {

        echo date('Y-m-d H:i:s') . ':' . $msg . "\r\n";
        return true;
    }

}