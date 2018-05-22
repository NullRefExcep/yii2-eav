<?php

namespace nullref\eav\models\attribute;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OptionSearch represents the model behind the search form about `nullref\eav\models\attribute\Option`.
 */
class OptionSearch extends Option
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'attribute_id', 'sort_order'], 'integer'],
            [['value'], 'safe'],
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
        $query = Option::find();

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
            'attribute_id' => $this->attribute_id,
            'sort_order' => $this->sort_order,
        ]);

        $query->andFilterWhere(['like', 'value', $this->value]);

        return $dataProvider;
    }
}
