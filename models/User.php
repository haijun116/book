<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $uid 用户uid
 * @property string $nickname 用户名
 * @property string $mobile 手机号码
 * @property string $email 邮箱地址
 * @property int $sex 1：男 2：女 0：没填写
 * @property string $avatar 头像
 * @property string $login_name 登录用户名
 * @property string $login_pwd 登录密码
 * @property string $login_salt 登录密码的随机加密秘钥
 * @property int $status 1：有效 0：无效
 * @property string $updated_time 最后一次更新时间
 * @property string $created_time 插入时间
 */
class User extends \yii\db\ActiveRecord
{
    public function setPassword($password)
    {
        $this->login_pwd = $this->getSaltPassword($password);
    }

    public function setSalt($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
        $salt = '';
        for ($i = 0; $i < $length; $i++) {
            $salt .= $chars[mt_rand(0, strlen($chars) - 1)];
        }

        $this->login_salt = $salt;
    }

    public function getSaltPassword($password)
    {

        return md5($password . md5($this->login_salt));
    }

    public function verifyPassword($password)
    {
        return $this->login_pwd === $this->getSaltPassword($password);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['updated_time', 'created_time'], 'safe'],
            [['nickname', 'email'], 'string', 'max' => 100],
            [['mobile', 'login_name'], 'string', 'max' => 20],
            [['sex', 'status'], 'string', 'max' => 1],
            [['avatar'], 'string', 'max' => 64],
            [['login_pwd', 'login_salt'], 'string', 'max' => 32],
            [['login_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'nickname' => 'Nickname',
            'mobile' => 'Mobile',
            'email' => 'Email',
            'sex' => 'Sex',
            'avatar' => 'Avatar',
            'login_name' => 'Login Name',
            'login_pwd' => 'Login Pwd',
            'login_salt' => 'Login Salt',
            'status' => 'Status',
            'updated_time' => 'Updated Time',
            'created_time' => 'Created Time',
        ];
    }
}
