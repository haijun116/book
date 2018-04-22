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

class QrcodeController extends Controller
{

    public function actionIndex()
    {
        $this->layout = false;
        return $this->render('index');

    }

    public function actionSet()
    {
        $this->layout = false;
        return $this->render('set');
    }




}