<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Courses;

/**
 * CoursesSearch represents the model behind the search form of `app\models\Courses`.
 */
class CoursesSearch extends Courses
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_project', 'user_id', 'plan_id', 'tf_status', 'tf_value', 'cpm', 'cpc', 'cpa', 'cpl', 'cps', 'today', 'total'], 'integer'],
            [['tf_type', 'is_online', 'is_h5', 'ad_sc_title', 'ad_type', 'tag_ids', 'logo', 'img_html', 'tags', 'create_at', 'update_at', 'zhaopin_card', 'highpin_card', 'zhaopin_html', 'message_text'], 'safe'],
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
        $query = Courses::find();

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
            'id_project' => $this->id_project,
            'user_id' => $this->user_id,
            'plan_id' => $this->plan_id,
            'tf_status' => $this->tf_status,
            'tf_value' => $this->tf_value,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
            'cpm' => $this->cpm,
            'cpc' => $this->cpc,
            'cpa' => $this->cpa,
            'cpl' => $this->cpl,
            'cps' => $this->cps,
            'today' => $this->today,
            'total' => $this->total,
        ]);

        $query->andFilterWhere(['like', 'tf_type', $this->tf_type])
            ->andFilterWhere(['like', 'is_online', $this->is_online])
            ->andFilterWhere(['like', 'is_h5', $this->is_h5])
            ->andFilterWhere(['like', 'ad_sc_title', $this->ad_sc_title])
            ->andFilterWhere(['like', 'ad_type', $this->ad_type])
            ->andFilterWhere(['like', 'tag_ids', $this->tag_ids])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'img_html', $this->img_html])
            ->andFilterWhere(['like', 'tags', $this->tags])
            ->andFilterWhere(['like', 'zhaopin_card', $this->zhaopin_card])
            ->andFilterWhere(['like', 'highpin_card', $this->highpin_card])
            ->andFilterWhere(['like', 'zhaopin_html', $this->zhaopin_html])
            ->andFilterWhere(['like', 'message_text', $this->message_text]);

        return $dataProvider;
    }
}
