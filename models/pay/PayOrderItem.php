<?php

namespace app\models\pay;

use Yii;

/**
 * This is the model class for table "pay_order_item".
 *
 * @property int $id
 * @property int $pay_order_id 订单id
 * @property int $member_id 会员id
 * @property int $quantity 购买数量 默认1份
 * @property string $price 商品总价格，售价 * 数量
 * @property string $discount 当前折扣
 * @property int $target_type 商品类型 1:书籍
 * @property int $target_id 对应不同商品表的id字段
 * @property string $note 备注信息
 * @property int $status 状态：1：成功 0 失败
 * @property int $comment_status 评价状态 1：已评价，0 ：未评价
 * @property string $updated_time 最近一次更新时间
 * @property string $created_time 插入时间
 */
class PayOrderItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pay_order_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pay_order_id', 'member_id', 'quantity', 'target_id'], 'integer'],
            [['price', 'discount'], 'number'],
            [['note'], 'required'],
            [['note'], 'string'],
            [['updated_time', 'created_time'], 'safe'],
            [['target_type'], 'string', 'max' => 4],
            [['status', 'comment_status'], 'string', 'max' => 1],
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
            'member_id' => 'Member ID',
            'quantity' => 'Quantity',
            'price' => 'Price',
            'discount' => 'Discount',
            'target_type' => 'Target Type',
            'target_id' => 'Target ID',
            'note' => 'Note',
            'status' => 'Status',
            'comment_status' => 'Comment Status',
            'updated_time' => 'Updated Time',
            'created_time' => 'Created Time',
        ];
    }
}
