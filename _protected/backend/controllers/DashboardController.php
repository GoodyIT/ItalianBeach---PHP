<?php

namespace backend\controllers;

use Yii;
use yii\helpers\Url;
use frontend\models\Bookhistory;
use frontend\models\Bookinfo;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class DashboardController extends \common\component\BaseController
{
    public function actionIndex()
    {
    	$totalRevenue = 0;
    	$Revenue = Bookinfo::getTotoalRevenueBySunshade($totalRevenue);

        $totalCustomers = 0;
        $Customers = Bookinfo::getTotalCustomersBySunshade($totalCustomers);

        $totalBooked = 0;
        $Booked = Bookinfo::getTotalBookedSunshade($totalBooked);

        $dailyCustomers = Bookinfo::getDailyCustomers();
        // $dailyCustomers = Bookinfo::getAlternativeDailyCustomers();

        // print_r($Customers);
        // return;

        $bestCustomers = Bookinfo::findBestCustomers();
        return $this->render('index', [
        		'Revenue' => $Revenue,
                'totalRevenue' => $totalRevenue,
                'Customers' => $Customers,
        		'totalCustomers' => $totalCustomers,
                'totalBooked' => $totalBooked,
                'Booked' => $Booked,
                'dailyCustomers' => $dailyCustomers,
                'bestCustomers' => $bestCustomers 
        	]);
    }
}
