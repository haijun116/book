<?php

namespace app\models\member;

use Yii;

/**
 * This is the model class for table "member_comments".
 *
 * @property int $id
 * @property int $member_id 会员id
 * @property int $book_id 商品id
 * @property int $pay_order_id 订单id
 * @property int $score 评分
 * @property string $content 评论内容
 * @property string $created_time 插入时间
 */
class MemberComments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'member_comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'book_id', 'pay_order_id'], 'integer'],
            [['created_time'], 'safe'],
            [['score'], 'string', 'max' => 4],
            [['content'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'book_id' => 'Book ID',
            'pay_order_id' => 'Pay Order ID',
            'score' => 'Score',
            'content' => 'Content',
            'created_time' => 'Created Time',
        ];
    }
}
