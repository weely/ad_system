<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property string $id
 * @property int $is_admin 0、广告主；1、管理员；2、代理商
 * @property string $username 编号
 * @property string $showname 名称
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
class User extends \yii\db\ActiveRecord  implements \yii\web\IdentityInterface
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
            [['username'], 'string', 'max' => 20],
            [['showname'], 'string', 'max' => 30],
            [['password', 'mobile'], 'string', 'max' => 12],
            [['cpx'], 'string', 'max' => 3],
            [['token'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['username', 'showname'], 'unique', 'targetAttribute' => ['username', 'showname']],
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
            'username' => 'Username',
            'showname' => 'Showname',
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
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
//        return static::findOne($token);
//        return static::findOne(['token' => $token]);
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
            ->where(['username'=>$username])
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
//        return $this->password === md5($password);
        return $this->password === $password;
    }
}
