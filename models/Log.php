<?php
namespace app\models;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property int $id
 * @property int $users_id
 * @property string $date_add
 * @property string $post
 * @property string $model
 * @property string $action
 *
 * @property Users $users
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['users_id'], 'integer'],
            [['date_add'], 'safe'],
            [['post'], 'string'],
            [['model'], 'string', 'max' => 255],
            [['action'], 'string', 'max' => 255],
            [['users_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['users_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'users_id' => 'Сотрудник',
            'date_add' => 'Дата запроса',
            'post' => 'Запрос',
            'model' => 'Модель',
            'action' => 'Действик',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'users_id']);
    }
}
