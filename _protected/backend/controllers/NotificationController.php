<?php

namespace backend\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\Notification;
use frontend\models\Cart;
use frontend\models\CartMilestone;
use common\models\User;
use frontend\models\Notificationreminder;
use frontend\models\BookLookup;
use yii\filters\AccessControl;

class NotificationController extends \common\component\BaseController
{
    public function actionIndex() {
        $lang = Yii::$app->request->get('lang');

        // check and update the upcomming available sunshade
        $onedayAvailable = "";
        $twodayAvailable = "";
        $result = array();
        $todayAvailable =  BookLookup::getAllAvailableSunshade(0);
        if (count($todayAvailable)) {
            $result['todayAvailable'] = $todayAvailable;
        } else {
            $onedayAvailable =  BookLookup::getAllAvailableSunshade(1);
            if (count($onedayAvailable)) {
                $result['onedayAvailable'] = $onedayAvailable;
            } else {
                $twodayAvailable =  BookLookup::getAllAvailableSunshade(2);
                if (count($twodayAvailable)) {
                    $result['twodayAvailable'] = $twodayAvailable;
                }                    
            }
        }

        if (!Notificationreminder::checkCartExpire()) {
            Cart::checkExpire();
            CartMilestone::checkExpire();
            Notificationreminder::updateCartExipreReminder();
        }

        if (!Notificationreminder::checkSunshadeReminder()) {
            BookLookup::checkExpiredate();
            Notificationreminder::updateSunshadeReminder();
        }

        $notification = Notification::getNofications($lang);
       
        $result['notification'] = $notification;

    	return \yii\helpers\Json::encode($result);
    }

    public function actionUpdatenotification() {
        
        $listOfIds = (array) Yii::$app->request->post('listOfIds');
        $result = Notification::updateNotification(implode(",", $listOfIds));

        // print_r(implode(",", $listOfIds));
        return;
    }

    public function actionTest() {
        $result =  Notification::eraseNotification([30,29]);
        
        print_r($result);
        return;
    }
}