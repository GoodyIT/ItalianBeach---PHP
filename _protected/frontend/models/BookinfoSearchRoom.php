<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Bookinfo;

/**
 * BookinfoSearch represents the model behind the search form about `frontend\models\Bookinfo`.
 */
class BookinfoSearchRoom extends Bookinfo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Id', 'bookId', 'guestId'], 'integer'],
            [['x', 'y'], 'number'],
            [['bookstate', 'seat', 'bookingdate'], 'safe'],
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
        $query = Bookinfo::find();

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
            'x' => $this->x,
            'y' => $this->y,
            'bookId' => $this->bookId,
            'guestId' => $this->guestId,
        ]);

        $query->andFilterWhere([
            '>=', 'Id', 176
        ]);

        $query->andFilterWhere(['like', 'bookstate', $this->bookstate])
            ->andFilterWhere(['like', 'seat', $this->seat])
            ->andFilterWhere(['like', 'bookingdate', $this->bookingdate]);

        return $dataProvider;
    }
}
