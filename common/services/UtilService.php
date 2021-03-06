<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/16
 * Time: 14:48
 */

namespace app\common\services;


use yii\helpers\Html;

class UtilService
{

    public static function getIP()
    {

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $_SERVER['REMOTE_ADDR'] ? $_SERVER['REMOTE_ADDR'] : '';
    }

    public static function encode($display)
    {
        return Html::encode($display);
    }

    public static function getRootPath()
    {
        return dirname(\Yii::$app->vendorPath);
    }


    public static function isWechat()
    {
        $ug = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        if (stripos($ug, 'micromessenger') !== false) {
            return true;
        }
        return false;
    }

    /**
     *分页
     * display 表示 显示的页数，例如共20页，但是只显示10页
     */
    public static function ipagination($params)
    {
        $ret = [
            'previous' => true,
            'next' => true,
            'from' => 0,
            'end' => 0,
            'total_page' => 0,
            'current' => 0,
            'page_size' => intval($params['page_size']),
            'offset' => 0,
        ];
        $total = (int)$params['total_count'];
        $pageSize = (int)$params['page_size'];
        $page = (int)$params['page'];
        $display = (int)$params['display'];
        $total_page = (int)ceil($total / $pageSize);
        $total_page = $total_page ? $total_page : 1;

        if ($page <= 1) {
            $ret['previous'] = false;
        }
        if ($page >= $total_page) {
            $ret['next'] = false;
        }
        $semi = (int)ceil($display / 2);
        if ($page - $semi > 0) {
            $ret['from'] = $page - $semi;
        } else {
            $ret['from'] = 1;
        }
        if ($page + $semi <= $total_page) {
            $ret['end'] = $page + $semi;
        } else {
            $ret['end'] = $total_page;
        }
        $ret['total_count'] = $total;
        $ret['total_page'] = $total_page;
        $ret['current'] = $page;
        $ret['offset'] = max([0, ($page - 1) * $ret['page_size']]);
        return $ret;
    }
}