<?php

namespace app\modules\web\controllers;

use app\models\brand\BrandImages;
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
        return $this->render('info', [
            'info' => $info
        ]);
    }

    public function actionSet()
    {

        if (\Yii::$app->request->isGet) {
            $info = BrandSetting::find()->one();
            return $this->render('set', [
                'info' => $info
            ]);
        }
        $name = trim($this->post('name'));
        $mobile = trim($this->post('mobile'));
        $address = trim($this->post('address'));
        $description = trim($this->post('description'));
        $image_key = trim($this->post('image_key'));
        if (mb_strlen($name, 'utf-8') < 1) {
            return $this->renderJSON([], '品牌名称不合法', -1);
        }
        if (!$image_key) {
            return $this->renderJSON([], '请上传品牌的Logo', -1);
        }
        if (mb_strlen($mobile, 'utf-8') < 1) {
            return $this->renderJSON([], '电话名称不合法', -1);
        }
        if (mb_strlen($address, 'utf-8') < 1) {
            return $this->renderJSON([], '地址名称不合法', -1);
        }
        if (mb_strlen($description, 'utf-8') < 1) {
            return $this->renderJSON([], '品牌介绍不合法', -1);
        }
        $info = BrandSetting::find()->one();
        $date_time = date('Y-m-d H:i:s');
        if ($info) {
            $model_brand = $info;
        } else {
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
        return $this->renderJSON([], '操作成功');
    }

    public function actionSetImage()
    {
        $image_key = trim($this->post('image_key' . ''));
        if (!$image_key) {
            return $this->renderJSON([], '请上传图片', -1);

        }
        $total_count = BrandImages::find()->count();
        if ($total_count > 5) {
            return $this->renderJSON([], '最多上传5张', -1);
        }
        $model = new BrandImages();
        $model->image_key = $image_key;
        $model->created_time = date('Y-m-d H:i:s');
        $model->save(0);
        return $this->renderJSON([], '操作成功');
    }

    public function actionImages()
    {
        $list = BrandImages::find()->orderBy(['id' => SORT_DESC])->all();
        return $this->render('images', [
            'list' => $list
        ]);
    }

    public function actionImageOps()
    {
        if (!\Yii::$app->request->isPost) {
            return $this->renderJSON([], '未知错误', -1);
        }
        $id = $this->post('id', []);
        if (!$id) {
            return $this->renderJSON([], '请选择要删除的图片', -1);
        }

        $info = BrandImages::find()->where(['id' => $id])->one();
        if (!$info) {
            return $this->renderJSON([], '指定的图片不存在', -1);
        }

        $info->delete();
        return $this->renderJSON([], '操作成功');
    }
}
