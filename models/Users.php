<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Users".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $date_bd
 * @property string $date_workstart
 * @property int $places_id
 * @param int $places_id
 * @property int $role
 * @param int $role
 * @property string $email
 * @property string $password
 * @property string $password_new
 * @property string $auth_key
 * @property string $access_token
 */
class Users extends \yii\db\ActiveRecord
{

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public $password_new = '';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_bd', 'date_workstart', 'role'], 'safe'],
            [['email', 'places_id', 'role'], 'required'],
            [['places_id'], 'integer'],
            [['first_name', 'last_name'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 30],
            [['email'], 'email'],
            [['email'], 'unique'],
            [['password', 'auth_key', 'access_token'], 'string', 'max' => 32],
            [['places_id'], 'exist', 'skipOnError' => true, 'targetClass' => Places::className(), 'targetAttribute' => ['places_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'date_bd' => 'Дата рождения',
            'date_workstart' => 'Работает с',
            'places_id' => 'Заведение',
            'role' => 'Права',
            'email' => 'Email',
            'password' => 'Пароль',
            'auth_key' => 'Авторизация',
            'access_token' => 'Токен',
            'password_new' => 'Пароль',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaces()
    {
        return $this->hasOne(Places::className(), [ 'id' => 'places_id' ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function findByEmail($email)
    {
        return static::findOne([ 'email' => $email ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function findByPasswordResetToken($token)
    {
        return static::findOne([ 'access_token' => $token ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function isPasswordResetTokenValid($token)
    {

        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public function generateAccessToken()
    {
         return Yii::$app->security->generateRandomString();
    }

    public function removePasswordResetToken()
    {
        $this->access_token = null;
    }
}