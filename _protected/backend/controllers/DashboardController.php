<?php

namespace backend\controllers;

use Yii;
use yii\helpers\Url;
use frontend\models\BookLookup;
use frontend\models\General;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class DashboardController extends \common\component\BaseController
{
    public function actionIndex()
    {
        $from = Yii::$app->request->get('from');
        $to = Yii::$app->request->get('to');

        if (empty($from)) {
            $from = \DateTime::createFromFormat('Y-m-d', date('Y')."-01-01")->format('Y-m-d');
        }

        if (empty($to)){
            $to = \DateTime::createFromFormat('Y-m-d', date('Y')."-12-31")->format('Y-m-d');
        }

        $from = date_format(date_create($from), 'Y-m-d');
        $to = date_format(date_create($to), 'Y-m-d');


    	$totalRevenue = 0;
    	$Revenue = BookLookup::getTotoalRevenueBySunshade($totalRevenue, $from, $to);

        $totalCustomers = 0;
        $Customers = BookLookup::getTotalCustomersBySunshade($totalCustomers, $from, $to);

        $totalBooked = 0;
        $Booked = BookLookup::getTotalBookedSunshade($totalBooked, $from, $to);

        $dailyCustomers = BookLookup::getDailyCustomers($from, $to);
        // $dailyCustomers = Bookinfo::getAlternativeDailyCustomers();

        $bestCustomers = BookLookup::findBestCustomers($from, $to);
        return $this->render('index', [
    		'Revenue' => $Revenue,
            'totalRevenue' => $totalRevenue,
            'Customers' => $Customers,
    		'totalCustomers' => $totalCustomers,
            'totalBooked' => $totalBooked,
            'Booked' => $Booked,
            'dailyCustomers' => $dailyCustomers,
            'bestCustomers' => $bestCustomers,
            'from' => $from,
            'to' => $to
    	]);
    }
}
