<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/6
 * Time: 15:19
 */

namespace app\common\services;

//文件的上传服务
class UploadService extends BaseService
{
    /**
     * @param $file_name 文件名
     * @param $file_path 文件路径
     * @param string $bucket 文件的上传的分类
     */
    public static function uploadByFile($file_name, $file_path, $bucket = '')
    {

        if (!$file_name) {
            return self::_err('文件名是必须的');
        }

        if (!$file_name || !file_exists($file_path)) {
            return self::_err('请输入合法的参数file_path');
        }
        $upload_config = \Yii::$app->params['upload'];
        if (!isset($upload_config[$bucket])) {
            return self::_err('指定的bucket参数错误');
        }

        $hash_key = md5(file_get_contents($file_path));
        $extend_type = explode('.', $file_name)[1];
        //在每个篮子下面按照日期放照片
        $upload_dir_path = UtilService::getRootPath() . '/web/' . $upload_config[$bucket] . '/';
        $foler_name = date('Ymd');
        $upload_dir = $upload_dir_path . $foler_name;
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777);
            chmod($upload_dir, 0777);
        }

        $upload_full_name = $foler_name . '/' . $hash_key . ".{$extend_type}";

        if(is_uploaded_file($file_path)){
            move_uploaded_file($file_path,$upload_dir_path.$upload_full_name);
        }else{
            file_put_contents($upload_dir.$upload_full_name,file_get_contents($file_path));
        }

        return [
            'code' => 200,
            'path' => $upload_full_name,
            'prefix' => $upload_config[$bucket]
        ];
    }
}