<?php
/**
 * Class UserController
 */

namespace app\modules\web\controllers;


use app\common\services\ConstantMapService;
use app\common\services\UtilService;
use app\models\log\AppAccessLog;
use app\modules\web\controllers\common\BaseController;

class DashboardController extends BaseController
{

    public function actionIndex()
    {
        $this->layout = 'main';
        return $this->render('index');

    }

}