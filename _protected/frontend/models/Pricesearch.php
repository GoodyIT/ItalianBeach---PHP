<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Price;

/**
 * Pricesearch represents the model behind the search form about `frontend\models\Price`.
 */
class Pricesearch extends Price
{
    /**
     * @inheritdoc
     */
    
    public $pricename;
    public function rules()
    {
        return [
            [['Id', 'servicetype', 'mainprice', 'tax', 'supplement', 'maxguests'], 'integer'],
            [['rowid'], 'safe'],
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
        $query = Price::find();

        $query->join('LEFT JOIN', 'tbl_servicetype', 'tbl_price.servicetype_Id = tbl_servicetype.id');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);     
        
        $dataProvider->setSort([
            'attributes' => [
                'servicetype.servicename' => [
                    'asc' => ['servicetype_Id' =>SORT_ASC],
                    'desc' => ['servicetype_Id' =>SORT_DESC],
                ],
                'rowid',
                'mainprice',
                'tax',
                'supplement',
                'maxguests',
            ]
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
            'mainprice' => $this->mainprice,
            'tax' => $this->tax,
            'supplement' => $this->supplement,
            'maxguests' => $this->maxguests,
        ]);

        $query->andFilterWhere(['like', 'rowid', $this->rowid])
                ->andFilterWhere(['like', 'tbl_servicetype.servicename', $this->servicetype_Id])->orderBy('rowid');
        return $dataProvider;
    }
}
