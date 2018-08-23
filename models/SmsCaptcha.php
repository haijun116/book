<?php

namespace app\models;

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
