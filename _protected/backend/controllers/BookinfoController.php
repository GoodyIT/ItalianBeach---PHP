<?php

namespace backend\controllers;

use Yii;
use frontend\models\Bookinfo;
use frontend\models\Book;
use frontend\models\Guestmilestone;
use frontend\models\Guest;
use frontend\models\BookinfoSearch;
use frontend\models\BookinfoSearchRoom;

// use frontend\models\Bookhistory;
use frontend\models\Notificationreminder;
use frontend\models\Notification;
use frontend\models\Rowrestriction;
use frontend\models\Price;
use common\models\User;
use frontend\models\Servicetype;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

/**
 * BookinfoController implements the CRUD actions for Bookinfo model.
 */
class BookinfoController extends \common\component\BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'request-password-reset', 'reset-password'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['bulk', 'index', 'view', 'create', 'bookinfo', 'bookupdate', 'logout', 'noprice', 'roombook'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionBookupdate(){
        $id = Yii::$app->request->get('id');
        $selectedId = Yii::$app->request->get('selectedId');
        $guestId = Bookinfo::getGuestId($id);

        $sunshade = $this->findModel($selectedId)->seat;

        $lang = Yii::$app->request->get('lang');
        

        $rowRestrictionLists = Price::getRowristriction();
        $priceLists = Price::getAllInfo();
        $serviceTypeLists = Servicetype::getAllInfo($lang);

        $guest = Guest::findOne($guestId['guestId']);


         if (Yii::$app->request->post('Book')) {
            
            $array=Yii::$app->request->post('Book');
            $array_price = Yii::$app->request->post('Milestone_Price');
            $array_date = Yii::$app->request->post('Milestone_Date');

            $paidAmount = array_sum($array_price);
            $bookId = Book::saveWithPost($array, $paidAmount);
            $timestamp = time(); // milestonegroupId for tbl_bookinfo & tbl_getmilestone
            Bookinfo::updateInfo($array['sunshadeseat'], $guestId['guestId'], $bookId, "booked", $timestamp);
            // Bookhistory::saveHistory($guestId, "booked", $array['sunshadeseat'], $bookId, $paidAmount, $array['guests'],  $_SERVER['REMOTE_ADDR']);

          // Milestone
            $array_price = Yii::$app->request->post('Milestone_Price');
            $array_date = Yii::$app->request->post('Milestone_Date');
            $return = Guestmilestone::saveWithGuestId($array_price, $array_date, $guestId, $array['sunshadeseat'], $timestamp);

           // notification
            Notificationreminder::resetAdminReminder();
            $title = ' Made a Reservation on Sunshade ' .  $array['sunshadeseat'];
            $title_it = ' Ha prenotato il Parasole ' . $array['sunshadeseat'];

            Notification::saveNewNotification($title, $title_it, $array['sunshadeseat']);

            $guest = Bookinfo::getCurrentBookinfo($array['sunshadeseat']);
            $lang = Yii::$app->request->post('lang');
            
            $emailBody = $this->buildEmailBody($guest, $lang);
            $message = Yii::$app->mailer->compose()
                        ->setFrom(Yii::$app->params['supportEmail'])
                        ->setTo($guest['email'])
                        ->setSubject('Booking info')
                        ->setHtmlBody($emailBody)
                        ->send();
            
            $adminEmails = User::getAllEmails();
            foreach ($adminEmails as $key => $value) {
                $message =   Yii::$app->mailer->compose()
                        ->setFrom(Yii::$app->params['supportEmail'])
                        ->setTo($value['email'])
                        ->setSubject('Booking info')
                        ->setHtmlBody($emailBody)
                        ->send();
            }        
            
           return $this->redirect(Url::to(['view', 'id' => $guest['Id']]));
        } else {
            $seat = $sunshade[0];
        if ($seat == 1) {
            $seat = $sunshade;
        }
        
        if (!isset($rowRestrictionLists[$seat])) {
            return $this->redirect(Url::to(['noprice', 'seat' => $seat]));
        }
        $rowRestriction = $rowRestrictionLists[$seat];

            return $this->render('bookupdate', [
                'id' => [$id],
                'guest' => $guest,
                'selectedId' => $selectedId,
                'seat' => $seat,
                'rowRestriction' => $rowRestriction,
                'sunshade' => $sunshade,
                'rowRestrictionLists' => $rowRestrictionLists,
                'priceLists' => $priceLists,
                'serviceTypeLists' => $serviceTypeLists,
            ]);
        }
    }
   
    public function actionBulk(){
        $selection= (array)Yii::$app->request->post('selection');
        if (empty($selection)) {
            $selection= (array)Yii::$app->request->get('selection');
            if (empty($selection)) {
                return $this->redirect(Url::to(['index']));
            }
        }

        $sunshade = $this->findModel($selection)->seat;

        $lang = Yii::$app->request->get('lang');
        if (!$lang) {
            $lang = Yii::$app->language;
        }

        $rowRestrictionLists = Price::getRowristriction();
        $priceLists = Price::getAllInfo();
        $serviceTypeLists = Servicetype::getAllInfo($lang);

        $guest = new Guest();

        if (Yii::$app->request->post('Book')) {
            
            $guestId =Guest::saveWithPost(Yii::$app->request->post('Guest'), "Booked");

            $array=Yii::$app->request->post('Book');
            $array_price = Yii::$app->request->post('Milestone_Price');
            $array_date = Yii::$app->request->post('Milestone_Date');
            
            $paidAmount = array_sum($array_price);
            $bookId = Book::saveWithPost($array, $paidAmount);
            $timestamp = time(); // milestonegroupId for tbl_bookinfo & tbl_getmilestone
            Bookinfo::updateInfo($array['sunshadeseat'], $guestId, $bookId, "booked", $timestamp);
            // Bookhistory::saveHistory($guestId, "booked", $array['sunshadeseat'], $bookId, $paidAmount, $array['guests'],  $_SERVER['REMOTE_ADDR']);

          // Milestone
            $array_price = Yii::$app->request->post('Milestone_Price');
            $array_date = Yii::$app->request->post('Milestone_Date');
            $return = Guestmilestone::saveWithGuestId($array_price, $array_date, $guestId, $array['sunshadeseat'], $timestamp);

           // notification
            Notificationreminder::resetAdminReminder();

             $title = ' Made a Reservation on Sunshade ' .  $array['sunshadeseat'];
            $title_it = ' Ha prenotato il Parasole ' . $array['sunshadeseat'];

            Notification::saveNewNotification($title, $title_it, $array['sunshadeseat']);

            $guest = Bookinfo::getCurrentBookinfo($array['sunshadeseat']);
            $lang = Yii::$app->request->post('lang');
            
            $emailBody = $this->buildEmailBody($guest, $lang);
            $message = Yii::$app->mailer->compose()
                        ->setFrom(Yii::$app->params['supportEmail'])
                        ->setTo($guest['email'])
                        ->setSubject('Booking info')
                        ->setHtmlBody($emailBody)
                        ->send();
            
            $adminEmails = User::getAllEmails();
            foreach ($adminEmails as $key => $value) {
                $message =   Yii::$app->mailer->compose()
                        ->setFrom(Yii::$app->params['supportEmail'])
                        ->setTo($value['email'])
                        ->setSubject('Booking info')
                        ->setHtmlBody($emailBody)
                        ->send();
            }        
            
           return $this->redirect(Url::to(['view', 'id' => $guest['Id'], 'lang' => $lang]));
        } else {
             $seat = $sunshade[0];
            if ($seat == 1) {
                $seat = $sunshade;
            }
            
            if (!isset($rowRestrictionLists[$seat])) {
                return $this->redirect(Url::to(['noprice', 'seat' => $seat]));
            }
            $rowRestriction = $rowRestrictionLists[$seat];


            return $this->render('bulkupdate', [
                'selection' => $selection,
                'guest' => $guest,
                'seat' => $seat,
                'rowRestriction' => $rowRestriction,
                'model' => $sunshade,
                'rowRestrictionLists' => $rowRestrictionLists,
                'priceLists' => $priceLists,
                'serviceTypeLists' => $serviceTypeLists,
            ]);
        }
    }

    public function actionNoprice($seat) {
        return $this->renderPartial('noprice', [
                'seat' => $seat
        ]);
    }

    /**
     * Lists all Bookinfo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BookinfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('bookinfo', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRoombook() {
        $searchModel = new BookinfoSearchRoom();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('roombook', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionBookinfo() {
      //  $bookinfolist = Bookinfo::getInfoForBackend();
        $searchModel = new \frontend\models\BookinfoSearch();
        
       $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('bookinfo', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bookinfo model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->post('Milestone_Price')) {
            $array_price = (array)Yii::$app->request->post('Milestone_Price');
            $array_date = (array)Yii::$app->request->post('Milestone_Date');
            $timestamp = Yii::$app->request->post('timestamp');
            $guestId = Yii::$app->request->post('guestId');
            $sunshadeseat = Yii::$app->request->post('sunshadeseat');
            $price = Yii::$app->request->post('price');
            $originPaidprice = Yii::$app->request->post('paidprice'); 
            $bookId = Yii::$app->request->post('bookId');
            $milestoneCompleteState = Yii::$app->request->post('milestoneCompleteState');
            
            $return = Guestmilestone::saveWithGuestId($array_price, $array_date, $guestId, $sunshadeseat, $timestamp);
            Bookinfo::updateTimestamp($sunshadeseat, $timestamp);

            if (!$milestoneCompleteState && array_sum($array_price) > 0) {
                $title = ' Update Milestones on Sunshade ' .  $array['sunshadeseat'];
                $title_it = ' Ha Aggiornato gli Acconti sul Parasole ' . $array['sunshadeseat'];

                Notification::saveNewNotification($title, $title_it, $sunshadeseat);
                Yii::$app->getDb()->createCommand('UPDATE `tbl_book` SET `paidprice`= :paidprice WHERE `Id` = :Id', [':paidprice' => $originPaidprice + array_sum($array_price), ':Id' => $bookId])->execute();
            }
        }

        $book = Book::getInfoWithId($model->bookId);
        $guest= Guest::findOne($model->guestId);
        $milestones = Guestmilestone::getMilestonesWithGuestId($model->guestId, $model->seat);

        return $this->render('view', [
            'model' => $model,
            'guest' => $guest,
            'book' => $book,
            'milestones' => $milestones
        ]);
    }

     /* Finds the Bookinfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bookinfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bookinfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
