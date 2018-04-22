<?php

namespace app\controllers;

use app\common\components\BaseWebController;

class DefaultController extends BaseWebController
{
    public function actionIndex(){
        $this->layout = 'main';
        return $this->render('index');
    }
}
