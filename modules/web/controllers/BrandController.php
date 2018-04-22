<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

/**
 * Default controller for the `web` module
 */
class BrandController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionInfo()
    {
        $this->layout = false;
        return $this->render('info');
    }

    public function actionSet()
    {
        $this->layout = false;
        return $this->render('set');
    }

    public function actionImage()
    {
        $this->layout = false;
        return $this->render('image');
    }
}
