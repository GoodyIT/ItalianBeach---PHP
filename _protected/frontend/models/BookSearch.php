<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Book;

/**
 * BookSearch represents the model behind the search form about `frontend\models\Book`.
 */
class BookSearch extends Book
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Id', 'servicetype', 'price', 'mainprice', 'tax', 'supplement', 'guests'], 'integer'],
            [['sunshadeseat', 'checkin', 'bookstate', 'bookedtime', 'comment'], 'safe'],
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
        $query = Book::find();

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
            'Id' => $this->Id,
            'servicetype' => $this->servicetype,
            'price' => $this->price,
            'mainprice' => $this->mainprice,
            'tax' => $this->tax,
            'supplement' => $this->supplement,
            'guests' => $this->guests,
        ]);

        $query->andFilterWhere(['like', 'sunshadeseat', $this->sunshadeseat])
            ->andFilterWhere(['like', 'checkin', $this->checkin])
            ->andFilterWhere(['like', 'bookstate', $this->bookstate])
            ->andFilterWhere(['like', 'bookedtime', $this->bookedtime])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
