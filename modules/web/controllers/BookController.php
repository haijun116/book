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

class BookController extends Controller
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

    public function actionInfo()
    {
        $this->layout = false;
        return $this->render('info');
    }

    public function actionImages(){
        $this->layout = false;
        return $this->render('images');
    }

    public function  actionCat(){

        $this->layout = false;
        return $this->render('cat');
    }

    public function actionCatSet(){

        $this->layout = false;
        return $this->render('cat_set');
    }
}