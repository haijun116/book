<?php

namespace app\modules\web\controllers;

use app\models\brand\BrandSetting;
use app\modules\web\controllers\common\BaseController;

/**
 * Default controller for the `web` module
 */
class BrandController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionInfo()
    {
        $info = BrandSetting::find()->one();
        return $this->render('info',[
            'info' => $info
        ]);
    }

    public function actionSet()
    {

        if (\Yii::$app->request->isGet){
            $info = BrandSetting::find()->one();
            return $this->render('set',[
                'info' =>$info
            ]);
        }
        $name = trim($this->post('name'));
        $mobile = trim($this->post('mobile'));
        $address = trim($this->post('address'));
        $description = trim($this->post('description'));
        $image_key = trim($this->post('image_key'));
        if(mb_strlen($name,'utf-8') < 1){
            return $this->renderJSON([],'品牌名称不合法',-1);
        }
        if(!$image_key){
            return $this->renderJSON([],'请上传品牌的Logo',-1);
        }
        if(mb_strlen($mobile,'utf-8') < 1){
            return $this->renderJSON([],'电话名称不合法',-1);
        }
        if(mb_strlen($address,'utf-8') < 1){
            return $this->renderJSON([],'地址名称不合法',-1);
        }
        if(mb_strlen($description,'utf-8') < 1){
            return $this->renderJSON([],'品牌介绍不合法',-1);
        }
        $info = BrandSetting::find()->one();
        $date_time = date('Y-m-d H:i:s');
        if($info){
            $model_brand = $info;
        }else{
            $model_brand = new BrandSetting();
            $model_brand->created_time = $date_time;
        }
        $model_brand->name = $name;
        $model_brand->mobile = $mobile;
        $model_brand->logo = $image_key;
        $model_brand->address = $address;
        $model_brand->description = $description;
        $model_brand->created_time = $date_time;
        $model_brand->save(0);
        return $this->renderJSON([],'操作成功');
    }

    public function actionImage()
    {

        return $this->render('image');
    }
}
