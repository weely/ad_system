<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form of `app\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_admin', 'day', 'total_fund', 'token_expire'], 'integer'],
            [['username', 'showname', 'password', 'cpx', 'mobile', 'token', 'create_at', 'update_at'], 'safe'],
            [['avail_fund'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'is_admin' => $this->is_admin,
            'day' => $this->day,
            'total_fund' => $this->total_fund,
            'avail_fund' => $this->avail_fund,
            'token_expire' => $this->token_expire,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'showname', $this->showname])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'cpx', $this->cpx])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'token', $this->token]);

        return $dataProvider;
    }
}
