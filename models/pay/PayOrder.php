<?php

namespace app\models\pay;

use Yii;

/**
 * This is the model class for table "pay_order".
 *
 * @property int $id
 * @property string $order_sn 随机订单号
 * @property int $member_id 会员id
 * @property int $target_type 商品类型 1:书籍
 * @property int $pay_type 1:微信 2：支付宝 3：银行转账 4: 现金 5:其他 6:刷卡
 * @property int $pay_source 下单来源:1:PC 2:m
 * @property string $total_price 订单应付金额
 * @property string $discount 优惠金额
 * @property string $pay_price 订单实付金额
 * @property string $pay_in_money 扣点后的所得金额
 * @property double $ratio 所扣点数
 * @property string $pay_sn 第三方流水号
 * @property string $note 备注信息
 * @property int $status 1：支付完成 0 无效 -1 申请退款 -2 退款中 -9 退款成功  -8 待支付  -7 完成支付待确认
 * @property int $express_status 快递状态，-8 待支付 -7 已付款待发货 1：确认收货 0：失败
 * @property int $express_address_id 快递地址id
 * @property string $express_info 快递单新
 * @property int $comment_status 评论状态
 * @property string $pay_time 付款到账时间
 * @property string $updated_time 最近一次更新时间
 * @property string $created_time 插入时间
 */
class PayOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pay_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'express_address_id'], 'integer'],
            [['total_price', 'discount', 'pay_price', 'pay_in_money', 'ratio'], 'number'],
            [['note'], 'required'],
            [['note'], 'string'],
            [['pay_time', 'updated_time', 'created_time'], 'safe'],
            [['order_sn'], 'string', 'max' => 40],
            [['target_type', 'pay_type', 'status', 'express_status'], 'string', 'max' => 4],
            [['pay_source', 'comment_status'], 'string', 'max' => 1],
            [['pay_sn'], 'string', 'max' => 128],
            [['express_info'], 'string', 'max' => 100],
            [['order_sn'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_sn' => 'Order Sn',
            'member_id' => 'Member ID',
            'target_type' => 'Target Type',
            'pay_type' => 'Pay Type',
            'pay_source' => 'Pay Source',
            'total_price' => 'Total Price',
            'discount' => 'Discount',
            'pay_price' => 'Pay Price',
            'pay_in_money' => 'Pay In Money',
            'ratio' => 'Ratio',
            'pay_sn' => 'Pay Sn',
            'note' => 'Note',
            'status' => 'Status',
            'express_status' => 'Express Status',
            'express_address_id' => 'Express Address ID',
            'express_info' => 'Express Info',
            'comment_status' => 'Comment Status',
            'pay_time' => 'Pay Time',
            'updated_time' => 'Updated Time',
            'created_time' => 'Created Time',
        ];
    }
}
