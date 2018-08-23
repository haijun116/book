<?php

namespace app\modules\m\controllers;

use app\models\brand\BrandImages;
use app\models\brand\BrandSetting;
use app\modules\m\controllers\common\BaseController;

/**
 * Default controller for the `m` module
 */
class DefaultController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $info = BrandSetting::find()->one();
        $images = BrandImages::find()->all();
        return $this->render('index',[
            'info' => $info,
            'images'=>$images
        ]);
    }
}
