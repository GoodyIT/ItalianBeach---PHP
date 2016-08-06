<?php

namespace backend\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\Notification;
use common\models\User;
use frontend\models\Notificationreminder;
use frontend\models\Bookinfo;
use yii\filters\AccessControl;

class NotificationController extends \common\component\BaseController
{

    public function actionIndex() {
        $lang = Yii::$app->request->get('lang');

        // check and update the upcomming available sunshade
        $onedayAvailable = "";
        $twodayAvailable = "";
        $todayAvailable = "";
        $result = array();
        if (!Notificationreminder::checkAdminReminder()) {
            Notificationreminder::updateAdminReminder();
            $todayAvailable =  Bookinfo::getAllAvailableSunshade(0);
            if (count($todayAvailable)) {
                $result['todayAvailable'] = $todayAvailable;
            } else {
                $onedayAvailable =  Bookinfo::getAllAvailableSunshade(1);
                if (count($onedayAvailable)) {
                    $result['onedayAvailable'] = $onedayAvailable;
                } else {
                    $twodayAvailable =  Bookinfo::getAllAvailableSunshade(2);
                    if (count($twodayAvailable)) {
                        $result['twodayAvailable'] = $twodayAvailable;
                    }                    
                }
            }
        }

        if (!Notificationreminder::checkSunshadeReminder()) {
            Bookinfo::checkExpiredate();
            Notificationreminder::updateSunshadeReminder();
        }

        $notification = Notification::getNofications($lang);
       
        $result['notification'] = $notification;
    	return \yii\helpers\Json::encode($result);
    }

    public function actionUpdatenotification() {
        
        $listOfIds = (array) Yii::$app->request->post('listOfIds');
        $result = Notification::updateNotification(implode(", ", $listOfIds));

        print_r($result);
        return;
    }

    public function actionTest() {
     $adminEmails = User::getAllEmails();
            for ($i = 0; $i < count($adminEmails); $i++) {
                print_r($adminEmails[$i]['email']);
            }
        
        
      return;
    }
}