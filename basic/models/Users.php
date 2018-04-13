<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property string $id
 * @property int $is_admin 0、广告主；1、管理员；2、代理商
 * @property string $user_number 编号
 * @property string $user_name 名称
 * @property string $password
 * @property int $cpx
 * @property string $day
 * @property string $total_fund 总资金
 * @property double $avail_fund 账户可用金额
 * @property string $mobile
 * @property string $token
 * @property string $token_expire
 * @property string $create_at
 * @property string $update_at
 */
class Users extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public $authKey;
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
            [['is_admin', 'day', 'total_fund', 'token_expire'], 'integer'],
            [['avail_fund'], 'number'],
            [['create_at', 'update_at'], 'safe'],
            [['user_number'], 'string', 'max' => 20],
            [['user_name'], 'string', 'max' => 30],
            [['password', 'mobile'], 'string', 'max' => 12],
            [['cpx'], 'string', 'max' => 3],
            [['token'], 'string', 'max' => 32],
            [['user_number'], 'unique'],
            [['token'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'is_admin' => 'Is Admin',
            'user_number' => 'User Number',
            'user_name' => 'User Name',
            'password' => 'Password',
            'cpx' => 'Cpx',
            'day' => 'Day',
            'total_fund' => 'Total Fund',
            'avail_fund' => 'Avail Fund',
            'mobile' => 'Mobile',
            'token' => 'Token',
            'token_expire' => 'Token Expire',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
//        return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
//        return static::findOne(['access_token' => $token]);
        return static::findOne($token);

    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $user = Users::find()
            ->where(['user_number'=>$username])
            ->asArray()
            ->one();

        if ($user) {
            return new static($user);
        } else {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}
