<?php

namespace app\models\book;

use Yii;

/**
 * This is the model class for table "book_cat".
 *
 * @property int $id
 * @property string $name 类别名称
 * @property int $weight 权重
 * @property int $status 状态 1：有效 0：无效
 * @property string $updated_time 最后一次更新时间
 * @property string $created_time 插入时间
 */
class BookCat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'book_cat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['updated_time', 'created_time'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['weight'], 'string', 'max' => 4],
            [['status'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'weight' => 'Weight',
            'status' => 'Status',
            'updated_time' => 'Updated Time',
            'created_time' => 'Created Time',
        ];
    }
}
