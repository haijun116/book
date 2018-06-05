<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/22
 * Time: 11:03
 */

namespace app\common\services;


class ConstantMapService
{
    public static $status_default = -1;
    public static $status_mapping = [
        1 => '正常',
        0 => '已删除'
    ];

    public static $sex_mapping = [
        1 => '男',
        2 => '女',
        0 => '未填写'
    ];
    public static $default_avatar = 'default_avatar';

    public static $default_password = '******';
}