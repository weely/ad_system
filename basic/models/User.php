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
            [['password', 'mobile'], 'string', 'max' => 20],
            [['username', 'showname'], 'unique', 'targetAttribute' => ['username', 'showname']],
//            [['username'], 'string', 'length'=>[2, 16], 'message'=>'账户编号请输入长度为2-16个字符'],
            ['username','required','message'=>'请输入用户编号'],
            ['username', 'string', 'length'=>[2, 16]],
            ['showname','required','message'=>'请输入用户名'],
            ['showname', 'string', 'length'=>[2, 16]],
            ['password','required','message'=>'密码不能为空'],
            ['password', 'string', 'length'=>[2, 24]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '用户ID',
            'is_admin' => '管理员(是1/否0)',
            'username' => '账户编号(字母、数字、下划线)',
            'showname' => '账户名',
            'password' => '密码',
            'cpx' => 'Cpx',
            'day' => 'Day',
            'total_fund' => '总资金',
            'avail_fund' => '可用资金',
            'mobile' => 'Mobile',
            'token' => 'Token',
            'token_expire' => 'Token Expire',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
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
        $user = User::find()
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
