<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/3
 * Time: 13:39
 */

namespace app\modules\weixin\controllers;


use app\common\components\BaseWebController;
use app\common\services\UrlService;
use app\models\book\Book;
use http\Url;
use yii\log\FileTarget;

class MsgController extends BaseWebController
{
    public function actionIndex(){
        if(!$this->checkSignature()){
            return 'Error For WeChat';
        }

        if(array_key_exists('echostr',$_GET) && $_GET['echostr']){
            return $this->get('echostr');
        }

        //获取post的xml数据
        $xml_data=file_get_contents('php://input');
        //$this->record_log("[xml_data]:".$xml_data);
        //解析数据
        if(!$xml_data){
            return 'error xml';
        }

        /*        $xml_data=<<<ETO
        <xml><ToUserName><![CDATA[gh_aad45ca8113c]]></ToUserName>
        <FromUserName><![CDATA[o08nhwx7vI8lgqejKQOu5t-mIkHg]]></FromUserName>
        <CreateTime>1521894679</CreateTime>
        <MsgType><![CDATA[text]]></MsgType>
        <Content><![CDATA[第一]]></Content>
        <MsgId>6536487874683921805</MsgId>
        </xml>
        ETO;*/

        $xml_obj=simplexml_load_string($xml_data,'SimpleXMLElement',LIBXML_NOCDATA);
        $from_username=$xml_obj->FromUserName;
        $to_username=$xml_obj->ToUserName;
        $msg_type=$xml_obj->MsgType;

        //var_dump($from_username,$to_username,$msg_type);

        $res=['type'=>'text','data'=>$this->defaultTip()];
        switch($msg_type){
            case 'text':
                $kw=trim($xml_obj->Content);
                $res=$this->search($kw); //数组[res,data]
                break;

        }

        //var_dump($res);
        switch($res['type']){
            case 'text':
                return $this->textTpl($from_username,$to_username,$res['data']);
                break;
            case 'rich':
                return $this->richTpl($from_username,$to_username,$res['data']);
                break;
        }

        //return 'Error EchoStr Empty!';
    }


    public function search($kw){
        $query=Book::find()->where(['status'=>1]);
        $like_name=['like','name',$kw];
        $like_tags=['like','tags',$kw];

        $query->andWhere(['or',$like_name,$like_tags]);
        $books_info=$query->orderBy(['id'=>SORT_DESC])->limit(3)->all();

        $data=$books_info?$this->getRichXml($books_info):$this->defaultTip();
        $type=$books_info?'rich':'text';

        return ['type'=>$type,'data'=>$data];
    }

    /**
     * [发送文本消息]
     * @param $from_username
     * @param $to_name
     * @param $content
     * @return string
     */
    private function textTpl($from_username,$to_name,$content){
        $tpl=<<<EOT
<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[%s]]></Content></xml>
EOT;
        return sprintf($tpl,$from_username,$to_name,time(),$content);

    }

    /**
     * [发送图文消息]
     * @param $from_username
     * @param $to_username
     * @param $data
     * @return string
     */
    //富文本
    private function richTpl( $from_username ,$to_username,$data){
        $tpl = <<<EOT
<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[news]]></MsgType>%s</xml>
EOT;
        return sprintf($tpl, $from_username, $to_username, time(), $data);
    }

    /**
     * [books_info数组组装成xml]
     * @param $list
     * @return string
     */
    private function getRichXml( $list ){
        $article_count = count( $list );
        $article_content = "";
        foreach($list as $_item){
            $tmp_description = mb_substr( strip_tags( $_item['summary'] ),0,20,"utf-8" );
            $tmp_pic_url = UrlService::buildPicUrl( "book",$_item['main_image'] );
            $tmp_url = UrlService::buildMUrl( "/product/info",[ 'id' => $_item['id'] ] );
            $article_content .= "<item><Title><![CDATA[{$_item['name']}]]></Title><Description><![CDATA[{$tmp_description}]]></Description><PicUrl><![CDATA[{$tmp_pic_url}]]></PicUrl><Url><![CDATA[{$tmp_url}]]></Url></item>";
        }

        $article_body = "<ArticleCount>%s</ArticleCount><Articles>%s</Articles>";
        return sprintf($article_body,$article_count,$article_content);
    }

    private function defaultTip(){
        $resData=<<<EOT
没有找到你想到的东西:(
EOT;
        return $resData;
    }

    public function checkSignature()
    {
        //_GET["signature"];
        //_GET["timestamp"];
        //_GET["nonce"];
        $signature=$this->get('signature');
        $timestamp=$this->get('timestamp');
        $nonce=$this->get('nonce');

        /*
         *  tmpArr = array(timestamp, $nonce);
            sort($tmpArr, SORT_STRING);
            $tmpStr = implode( $tmpArr );
            $tmpStr = sha1( $tmpStr );
         * */
        $token=\Yii::$app->params['weixin']['token'];
        $tmpArr=array($token,$timestamp,$nonce);
        sort($tmpArr,SORT_STRING);
        $tmpStr=implode($tmpArr);
        $tmpStr=sha1($tmpStr);

        if($signature == $tmpStr){
            return true;
        }else{
            return false;
        }
    }

    public function record_log($msg){
        $log=new FileTarget();
        $log->logFile=\Yii::$app->getRuntimePath().'/logs/weixin_msg_'.date('Ymd').'log';
        $request_url=isset($_SERVER['REQUEST_URI'])? $_SERVER['REQUEST_URI']:'';
        $log->messages[]=[
            "[url:{$request_url}][post:".http_build_query($_POST)."][msg:{$msg}]",
            1,
            'application',
            microtime(true)
        ];

        $log->export();
    }
}

