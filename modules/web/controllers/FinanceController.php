<?php
/**
 * Class UserController
 */

namespace app\modules\web\controllers;


use app\common\services\ConstantMapService;
use app\common\services\UrlService;
use app\common\services\UtilService;
use app\models\log\AppAccessLog;
use app\models\User;
use app\modules\web\controllers\common\BaseController;
use yii\web\Controller;

class FinanceController extends Controller
{

    public function actionIndex()
    {
        $this->layout = false;
        return $this->render('index');

    }

    public function actionAccount()
    {
        $this->layout = false;
        return $this->render('account');
    }

    public function actionPayInfo()
    {
        $this->layout = false;
        return $this->render('pay_info');
    }


}