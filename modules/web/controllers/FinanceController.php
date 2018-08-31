<?php
/**
 * Class UserController
 */

namespace app\modules\web\controllers;


use app\common\services\ConstantMapService;
use app\common\services\UrlService;
use app\common\services\UtilService;
use app\models\book\Book;
use app\models\log\AppAccessLog;
use app\models\pay\PayOrder;
use app\models\pay\PayOrderItem;
use app\models\User;
use app\modules\web\controllers\common\BaseController;
use yii\web\Controller;

class FinanceController extends BaseController
{

    public function actionIndex()
    {

        $status = intval($this->get('status', ConstantMapService::$status_default));
        $p = intval($this->get('p', 1));
        $p = ($p > 0) ? $p : 1;
        $pay_status_mapping = ConstantMapService::$pay_status_mapping;
        $query = PayOrder::find();
        if($status > ConstantMapService::$status_default){
             $query->andWhere(['status'=>$status]);
        }
        //分页，首先获取总页数，然后获取总页数的条数
        $page_size = 10; //每页显示的条数
        $total_count = $query->count();//总记录数
        $total_page = ceil($total_count / $page_size);
        $list = $query->orderBy([ 'id' => SORT_DESC ])
            ->offset(($p - 1) * $page_size)
            ->limit($page_size)
            ->asArray()
            ->all( );
        if($list){
            $order_item_list = PayOrderItem::find()->where(['pay_order_id'=>array_column($list,'id')])->asArray()->all();
            $book_mapping = Book::find()->select(['id','name'])
                ->where(['id'=>array_column($order_item_list,"target_id")])
                ->indexBy('id')
                ->all();

            $pay_order_mapping = [];
            foreach( $order_item_list as $_order_item_info ){
                $tmp_book_info = $book_mapping[ $_order_item_info['target_id'] ];
                if( !isset( $pay_order_mapping[ $_order_item_info['pay_order_id'] ] ) ){
                    $pay_order_mapping[ $_order_item_info['pay_order_id'] ] = [];
                }

                $pay_order_mapping[ $_order_item_info['pay_order_id'] ][] = [
                    'name' => $tmp_book_info['name'],
                    'quantity' => $_order_item_info['quantity']
                ];
            }

            foreach ($list as $_item){
                $data[] = [
                    'id' => $_item['id'],
                    'sn' => date("Ymd",strtotime( $_item['created_time'] ) ).$_item['id'],
                    'pay_price' => $_item['pay_price'],
                    'status_desc' => $pay_status_mapping[ $_item['status'] ],
                    'status' => $_item['status'],
                    'pay_time' => date("Y-m-d H:i",strtotime( $_item['pay_time'] ) ),
                    'created_time' => date("Y-m-d H:i",strtotime( $_item['created_time'] ) ),
                    'items' => isset( $pay_order_mapping[ $_item['id'] ] )?$pay_order_mapping[ $_item['id'] ]:[]
                ];
            }

        }
        return $this->render('index',[
            'pages' => [
                'total_count' => $total_count,
                'page_size' => $page_size,
                'total_page' => $total_page,
                'p' => $p
            ],
            'list' => $data,
            'search_conditions' => [
                'p' => $p,
                'status' => $status
            ],
            'status_mapping' => $pay_status_mapping
        ]);

    }

    public function actionAccount()
    {
        $p = $this->get('p',1);
        $p = $p>0 ? $p  :1;
        $query = PayOrder::find()->where(['status'=>1]);
        $page_size = 10; //每页显示的条数
        $total_res_count = $query->count();
        $total_pay_money = $query->sum('pay_price');
        $total_page = ceil($total_res_count / $page_size);
        $list =  $query->orderBy([ 'pay_time' => SORT_DESC ])
            ->offset(($p - 1) * $page_size)
            ->limit($page_size)
            ->asArray()
            ->all( );
        $data = [];
        if($list){
            foreach ($list as  $_item){
                $data [] = [
                    'id'=>$_item['id'],
                    'sn' => date("Ymd",strtotime( $_item['created_time'] ) ).$_item['id'],
                    'pay_price' => $_item['pay_price'],
                    'pay_time' => date("Y-m-d H:i",strtotime( $_item['pay_time'] ) )
                ];
            }
        }
        $total_pay_money = $total_pay_money ? $total_pay_money:0;

        return $this->render('account',[
            'pages' => [
                'total_count' => $total_res_count,
                'page_size' => $page_size,
                'total_page' => $total_page,
                'p' => $p
            ],
            'list' => $data,
            'search_conditions' => [
                'p' => $p,
            ],
            'total_pay_money' => sprintf("%.2f",$total_pay_money)
        ]);
    }

    public function actionPay_info()
    {
        return $this->render('pay_info');
    }

}