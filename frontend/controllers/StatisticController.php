<?php
namespace frontend\controllers;
use Yii;
use frontend\models\customer\Customer;
use frontend\models\customer\CustomerRecord;
use frontend\models\customer\Phone;
use frontend\models\customer\PhoneRecord;
use \yii\data\ArrayDataProvider;
use \yii\web\Controller;


class StatisticController extends Controller
{
	public function actionIndex()
	{
		$records = $this->findRecordsByQuery();
		return $this->render('index', compact('records'));
		
		// return "customer / index";
	}
	
	public function actionAdd()
	{
		
		$statistic = new StatisticRecord;
		$id = '2';
		$gameKat = 'tt';
		$game = '1';
		$userAnswere = 'Tu';
		$amoutOfTries = new time();
		$elapsedTime = new time();
		
		if ($this->load($statistic, $_POST)) {
				$this->store($this->makeStatistic($id, $gameKat, $game, $userAnswere, $amoutOfTries, $elapsedTime));
				return $this->redirect('/customer');
		}
		
		return $this->render('add',compactmakeStatistic($id, $gameKat, $game, $userAnswere, $amoutOfTries, $elapsedTime));
		
		// return "customer / add";
	}

	public function actionQuery() 
	{
		return $this->render('query');
	}
	
	private function load(CustomerRecord $customer, PhoneRecord $phone, array $post)
	{
		return $customer->load($post)
			and $phone->load($post)
			and $customer->validate()
			and $phone->validate(['number']);
	}
	
	private function findRecordsByQuery(){
		$number = Yii::$app->request->get('phone_number');
		$records = $this->getRecordsByPhoneNumber($number);
		$dataProvider = $this->wrapIntoDataProvider($records);
		return $dataProvider;
	}

	private function wrapIntoDataProvider($data)
	{
	   return new ArrayDataProvider([
	           'allModels' => $data,
	           'pagination' => false
	    ]); 
	}
	
	private function getRecordsByPhoneNumber($number) {
		$phone_record = PhoneRecord::findOne(['number' => $number]);
		if (!$phone_record)
			return [];
		
		$customer_record = CustomerRecord::findOne($phone_record->customer_id);
		if (!$customer_record)
			return [];
		
		return [$this->makeCustomer($customer_record, $phone_record)];
	}

	
	private function store(Customer $customer) {
		$customer_record = new CustomerRecord();
		$customer_record->name = $customer->name;
		$customer_record->birth_date = $customer->birth_date->format('Y-m-d');
		$customer_record->notes = $customer->notes;
		$customer_record->save();

		foreach ($customer->phones as $phone) {
			$phone_record = new PhoneRecord();
			$phone_record->number = $phone->number;
			$phone_record->customer_id = $customer_record->id;
			$phone_record->save();
		}
	}

	private function makeCustomer(CustomerRecord $customer_record, PhoneRecord $phone_record) {
		$name = $customer_record->name;
		$birth_date = new \DateTime($customer_record->birth_date);

		$customer = new Customer($name, $birth_date);
		$customer->notes = $customer_record->notes;
		$customer->phones[] = new Phone($phone_record->number);
		
		return $customer;
	}
}
