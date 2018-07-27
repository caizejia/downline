<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WmsWsBill;

/**
 * WmsWsBillSearch represents the model behind the search form of `common\models\WmsWsBill`.
 */
class WmsWsBillSearch extends WmsWsBill
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'action_user_id', 'status'], 'integer'],
            [['ref', 'action_time', 'customer_id', 'create_time', 'warehouse_id'], 'safe'],
            [['inventory_money'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = WmsWsBill::find();

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
            'action_time' => $this->action_time,
            'action_user_id' => $this->action_user_id,
            'create_time' => $this->create_time,
            'inventory_money' => $this->inventory_money,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'ref', $this->ref])
            ->andFilterWhere(['like', 'customer_id', $this->customer_id])
            ->andFilterWhere(['like', 'warehouse_id', $this->warehouse_id]);

        return $dataProvider;
    }
}
