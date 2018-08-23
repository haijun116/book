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

class StatController extends Controller
{

    public function actionIndex()
    {
        $this->layout = false;
        return $this->render('index');

    }

    public function actionProduct()
    {
        $this->layout = false;
        return $this->render('product');
    }

    public function actionMember()
    {
        $this->layout = false;
        return $this->render('member');
    }

    public function actionShare(){
        $this->layout = false;
        return $this->render('share');
    }

}