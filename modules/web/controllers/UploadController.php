<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/6
 * Time: 14:49
 */

namespace app\modules\web\controllers;


use app\common\services\UploadService;
use app\modules\web\controllers\common\BaseController;

class UploadController extends BaseController
{
    private $allow_type = [
        'jpg', 'jpeg', 'gif', 'png'
    ];

    /**
     * 上传的接口
     * @图片的类型 avatar/brand/book
     */
    public function actionPic()
    {

        $bucket = trim($this->post('bucket', ''));
        $callback = "window.parent.upload";  //error,success 调用父类的error,success方法
        if (!$_FILES || !isset($_FILES['pic'])) {
            return "<script>{$callback}.error('请选择文件之后再提交');</script>";
        }
        $file_name = $_FILES['pic']['name'];
        $file_extend = explode('.', $file_name);
        if (!in_array(strtolower(end($file_extend)), $this->allow_type)) {
            return "<script>{$callback}.error('请上传指定类型的图片,jpg,jpeg,gif,png');</script>";
        }

        //上传的图片的业务逻辑

        $res = UploadService::uploadByFile($file_name,$_FILES['pic']['tmp_name'],$bucket);
        if(!$res){
            return "<script>{$callback}.error('".UploadService::getLastErrorMsg()."');</script>";
        }

        return "<script>{$callback}.success('{$res['path']}');</script>";
    }
}