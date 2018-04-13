<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AdPlans;

/**
 * AdPlansSearch represents the model behind the search form of `app\models\AdPlans`.
 */
class AdPlansSearch extends AdPlans
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'old_plan_id', 'user_id', 'tf_status', 'tf_value', 'cpm', 'cpc', 'cps'], 'integer'],
            [['tag_ids', 'plan_number', 'plan_name', 'tf_type', 'tf_period', 'properties', 'year', 'sex', 'degree', 'create_at', 'update_at'], 'safe'],
            [['budget'], 'number'],
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
        $query = AdPlans::find();

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
            'old_plan_id' => $this->old_plan_id,
            'user_id' => $this->user_id,
            'tf_status' => $this->tf_status,
            'tf_value' => $this->tf_value,
            'budget' => $this->budget,
            'cpm' => $this->cpm,
            'cpc' => $this->cpc,
            'cps' => $this->cps,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'tag_ids', $this->tag_ids])
            ->andFilterWhere(['like', 'plan_number', $this->plan_number])
            ->andFilterWhere(['like', 'plan_name', $this->plan_name])
            ->andFilterWhere(['like', 'tf_type', $this->tf_type])
            ->andFilterWhere(['like', 'tf_period', $this->tf_period])
            ->andFilterWhere(['like', 'properties', $this->properties])
            ->andFilterWhere(['like', 'year', $this->year])
            ->andFilterWhere(['like', 'sex', $this->sex])
            ->andFilterWhere(['like', 'degree', $this->degree]);

        return $dataProvider;
    }
}
