<?php
/**
 * Created by PhpStorm.
 * User: lion
 * Date: 2018/7/30
 * Time: 下午6:46
 */

namespace app\common\services\weixin;


use app\common\components\HttpClient;

class PayApiService
{
    private $params = [];
    private $wxpay_params = [];
    private $prepay_id = null;
    public $prepay_info = null;

    public function __construct($wxpay_params)
    {
        $this->wxpay_params = $wxpay_params;
    }

    public function setWxpay_params($wxpay_params)
    {
        $this->wxpay_params = $wxpay_params;
    }

    public function setParameter($parameter, $parameterValue)
    {
        $this->params[$parameter] = $parameterValue;
    }

    public function getPrepayInfo()
    {
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        $this->params["nonce_str"] = $this->createNoncestr();
        $this->params['sign'] = $this->getSign($this->params);
        $xml_data = $this->arrayToXml($this->params);
        $ret = HttpClient::post($url, $xml_data);

    }

    public function setPrepayId($prepare_id)
    {
        $this->prepay_id = $prepare_id;
    }

    public function getParameters()
    {
        $jsApiObj['appId'] = $this->wxpay_params['appid'];
        $timeStamp = time();
        $jsApiObj["timeStamp"] = $timeStamp;
        $jsApiObj["nonceStr"] = $this->createNoncestr();
        $jsApiObj["package"] = "prepay_id=" . $this->prepay_id;
        $jsApiObj["signType"] = "MD5";
        $jsApiObj["paySign"] = $this->getSign($jsApiObj);
        return $jsApiObj;
    }


    /**
     * 把:array转换xml
     */
    public function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $value) {
            if (is_numeric($value)) {
                $xml .= "<" . $key . ">" . $value . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }

        }
        $xml .= "</xml>";
        return $xml;

    }

    /**
     * 生成签名
     * @param $Obj
     */

    private function getSign($Obj)
    {
        foreach ($Obj as $k => $v) {
            $Parameters[$k] = $v;
        }
        ksort($Parameters);
        $Sting = $this->formatBizQueryParMap();
        $Sting = $Sting . '&key=' . $this->wxpay_params['pay']['key'];
        $Sting = md5($Sting);
        $result = strtolower($Sting);
        return $result;
    }

    /**
     * 格式化签名参数
     * @param $paraMap 参数值
     * @param $urlcode  是否格式化
     */
    private function formatBizQueryParMap($paraMap, $urlcode)
    {
        $buff = '';
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if ($urlcode) {
                $v = urlencode($v);
            }
            $buff .= $k . '=' . $v . '&';
        }
        $repPar = '';
        if (strlen($buff) > 0) {
            $repPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $repPar;
    }

    /**
     * @param int $length
     * @return string
     */

    private function createNoncestr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     *  作用：将xml转为array
     */
    public function xmlToArray($xml)
    {
        //将XML转为array
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }

    public function checkSign($sign)
    {
        $tmpData = $this->params;
        $wxpay_sign = $this->getSign($tmpData);
        if ($wxpay_sign == $sign) {
            return TRUE;
        }
        return FALSE;
    }
}