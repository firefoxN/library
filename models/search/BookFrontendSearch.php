<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Book;

class BookFrontendSearch extends Model
{
    public $letter;
    public $searchString;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['letter', 'searchString'], 'safe'],
        ];
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

        $this->load($params, '');



        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if(!empty($this->letter)) {
            $query->filterWhere(['like', 'name', $this->letter.'%', false]);
        }

        if(!empty($this->searchString)) {
            $query->andfilterWhere(['like', 'name', $this->searchString]);
        }

        return $dataProvider;
    }
}