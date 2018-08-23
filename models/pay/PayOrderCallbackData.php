<?php

namespace app\models\pay;

use Yii;

/**
 * This is the model class for table "pay_order_callback_data".
 *
 * @property int $id
 * @property int $pay_order_id 支付订单id
 * @property string $pay_data 支付回调信息
 * @property string $refund_data 退款回调信息
 * @property string $updated_time 最后一次更新时间
 * @property string $created_time 创建时间
 */
class PayOrderCallbackData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pay_order_callback_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pay_order_id'], 'integer'],
            [['pay_data', 'refund_data'], 'required'],
            [['pay_data', 'refund_data'], 'string'],
            [['updated_time', 'created_time'], 'safe'],
            [['pay_order_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pay_order_id' => 'Pay Order ID',
            'pay_data' => 'Pay Data',
            'refund_data' => 'Refund Data',
            'updated_time' => 'Updated Time',
            'created_time' => 'Created Time',
        ];
    }
}
