<?php

namespace app\models\sms;

use Yii;

/**
 * This is the model class for table "sms_captcha".
 *
 * @property int $id
 * @property string $mobile
 * @property string $captcha
 * @property string $ip
 * @property string $expires_at
 * @property int $status
 * @property string $created_time
 */
class SmsCaptcha extends \yii\db\ActiveRecord
{

    //生成手机验证码
    public function generateCode($mobile, $ip)
    {

        $code = mt_rand(10000, 99999);

        $this->captcha = $code;
        $this->mobile = $mobile;
        $this->ip = $ip;
        $this->created_time = date('Y-m-d H:i:s');
        $this->expires_at = date('Y-m-d H:i:s', time() + 60);
        $this->status = 0;
        return $this->save(0);
    }

    //校验手机验证码
    public static function checkCode($mobile, $code)
    {

        $res = self::find()->where(['mobile' => $mobile, 'captcha' => $code])->one();

        if ($res && strtotime($res['expires_at']) >= time()) {
            $res->expires_at = date('Y-m-d H:i:s', time() - 1);
            $res->status = 1;
            $res->save(0);

            return true;

        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms_captcha';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['expires_at', 'created_time'], 'safe'],
            [['status'], 'required'],
            [['mobile', 'ip'], 'string', 'max' => 20],
            [['captcha'], 'string', 'max' => 10],
            [['status'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mobile' => 'Mobile',
            'captcha' => 'Captcha',
            'ip' => 'Ip',
            'expires_at' => 'Expires At',
            'status' => 'Status',
            'created_time' => 'Created Time',
        ];
    }
}
