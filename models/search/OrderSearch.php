<?php
namespace app\models\search;

use app\models\Order;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * OrderSearch represents the model behind the search form about `app\models\Order`.
 */
class OrderSearch extends Order {

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[
				[
					'id',
					'number',
					'event_id',
					'user_id',
					'agency_id',
					'discount_type',
					'total',
					'remain',
					'status',
				],
				'integer',
			],
			[
				[
					'customer_name',
					'customer_phone',
					'customer_address',
					'note',
					'updated_date',
					'booked_date',
					'edited',
				],
				'safe',
			],
			[
				['discount'],
				'number',
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function scenarios() {
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
	public function search($params) {
		$query = Order::find()->where([
			'<>',
			'status',
			0,
		])->orderBy([
			'status'      => SORT_DESC,
			'booked_date' => SORT_DESC,
			'id'          => SORT_DESC
		]);
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
		/** bắt đầu lọc phần edit */
		if($this->edited!=null) {
            $not_in = array();

            $models = self::find()->where(['in',['number', 'event_id'] ,self::find()->where(['status'=>0])->groupBy(['number','event_id'])->select(['number','event_id'])])
                ->andWhere(['<>','status',0])->all();

            /**@var Order[] $models */
            foreach ($models as $model) {
                    $not_in[] = $model->id;
            }

            $not_in = array_unique($not_in);


            if ($this->edited == 3) {
                $query->andWhere([
                    'in',
                    'id',
                    $not_in,
                ]);
            } else if ($this->edited == 2) {
                $query->andWhere([
                    'not in',
                    'id',
                    $not_in,
                ]);
            }
        }
		/**  kết thúc phần edit */
		$query->andFilterWhere([
			'id'            => $this->id,
			'number'        => $this->number,
			'event_id'      => $this->event_id,
			'user_id'       => $this->user_id,
			'agency_id'     => $this->agency_id,
			'discount'      => $this->discount,
			'discount_type' => $this->discount_type,
			'total'         => $this->total,
			'remain'        => $this->remain,
			'status'        => $this->status,
			'updated_date'  => $this->updated_date,
			'booked_date'   => $this->booked_date,
		]);
		$query->andFilterWhere([
			'like',
			'customer_name',
			$this->customer_name,
		])->andFilterWhere([
			'like',
			'customer_phone',
			$this->customer_phone,
		])->andFilterWhere([
			'like',
			'customer_address',
			$this->customer_address,
		])->andFilterWhere([
			'like',
			'note',
			$this->note,
		]);
		return $dataProvider;
	}
}
