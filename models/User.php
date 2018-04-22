<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $uid 管理员的id
 * @property string $nickname 用户名
 * @property string $mobile 用户手机号
 * @property string $email 邮箱
 * @property int $sex 性别1表示男，1女
 * @property string $avatar 头像key
 * @property string $login_name 登录用户名
 * @property string $login_pwd 登录密码
 * @property string $login_salt 加密随机秘钥
 * @property int $status 1为有效，0为无效
 * @property string $updated_time 最后一次更新时间
 * @property string $create_time 插入时间
 */
class User extends \yii\db\ActiveRecord
{
    //生成加密密码
    public function getSaltPassword($password){
        return md5($password . md5($this->login_salt));
    }

    //校验密码

    public function verifyPassword($password){
        return $this->getSaltPassword($password) == $this->login_pwd;
    }

    //设置密码
    public function setPassword($password){
        return $this->login_pwd = $this->getSaltPassword($password);
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
            [['nickname', 'mobile', 'email', 'avatar', 'login_name', 'login_pwd', 'login_salt'], 'required'],
            [['updated_time', 'create_time'], 'safe'],
            [['nickname', 'email'], 'string', 'max' => 100],
            [['mobile', 'login_name'], 'string', 'max' => 20],
            [['sex'], 'string', 'max' => 1],
            [['avatar'], 'string', 'max' => 64],
            [['login_pwd', 'login_salt'], 'string', 'max' => 32],
            [['status'], 'string', 'max' => 4],
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
            'create_time' => 'Create Time',
        ];
    }
}
