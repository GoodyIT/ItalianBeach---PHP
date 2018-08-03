<?php

namespace backend\controllers;

use Yii;
use frontend\models\Guest;
use frontend\models\Bookinfo;
use frontend\models\BookLookup;
use frontend\models\Book;
use frontend\models\Cart;
use frontend\models\General;
use frontend\models\CartMilestone;
use common\models\User;
use common\component\bookingcart;
use frontend\models\Price;
use frontend\models\GuestSearch;
use frontend\models\Notificationreminder;
use frontend\models\Notification;
use frontend\models\Guestmilestone;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ArrayDataProvider;
use yii\data\Sort;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


/**
 * GuestController implements the CRUD actions for Guest model.
 */
class GuestController extends \common\component\BaseController
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
                        'actions' => ['sendbookinfo', 'pdf', 'pdfall', 'guestdetail', 'sendemail', 'sendbulkemail', 'bookinfo','view', 'update', 'guestinfo', 'detailview', 'logout', 'create', 'delete', 'deletebooking', 'checkcustomerexistence', 'mapbooking', 'customerrecursive', 'gotocart', 'savetocart', 'removesunshadefromcartwithid', 'updatecart', 'readcart', 'readsunshades', 'readmilestone', 'addmilestonetocart'],
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

    public function clearCart() {
        $token = 'admin-' . $_COOKIE['clientId'];
        Cart::clearCart($token);
        CartMilestone::deleteMilestones($token);
        General::resetFromTo($token);
    }

    public function actionGuestinfo() {
        $guest = BookLookup::getAllBookDetailsByCustomers();

        return $this->render('guestinfo', [
            'guest' => $guest,
        ]);
    }

    public function actionMapbooking(){
        $from = Yii::$app->request->get('from');
        $to = Yii::$app->request->get('to');
        $token = 'admin-'.$_COOKIE['clientId'];
        if ($from == "" || $to == "") {
            $model = General::getFromTo($token);
            if (!empty($model)) {
                $from = $model['searchfrom'];
                $to = $model['searchto'];
            } else {
                $from = date('d M, Y');
                $to = new \DateTime($from);
                $to = $to->modify('+3 months')->format('d M, Y');
                General::saveFromTo($token, $from, $to);
            }
        } else
        {
            General::saveFromTo($token, $from, $to);
        }
        $jsonValue = BookLookup::getAllSunshadesWithinRange($from, $to, $token);
        $id = Yii::$app->request->get('id');
        $guest = Guest::getGuestInfo($id);

        return $this->render('mapbooking', [
            'jsonValue' => $jsonValue,
            'guest' => $guest,
            'from' => $from,
            'to' => $to,
            'id' => $id
        ]);
    }

    public function actionGotocart()
    {
        if (!isset($_COOKIE['clientId'])) {
            return $this->redirect(Url::to(['guest/mapbooking', 'lang' =>  Yii::$app->language]));
        }
        $token = 'admin-'. $_COOKIE['clientId'];
        $from = Yii::$app->request->get('from');
        $to = Yii::$app->request->get('to');
        if ($from == "" || $to == "") {
           $model = General::getFromTo($token);
            if (!empty($model)) {
                $from = $model['searchfrom'];
                $to = $model['searchto'];
            } else {
                $from = date('d M, Y');
                $to = new \DateTime($from);
                $to = $to->modify('+3 months')->format('d M, Y');
            }
        }
        $jsonValue = BookLookup::getAllSunshadesWithinRange($from, $to, $token);
        $price = Price::getAllInfoWithArray();
        $myCart = Cart::getAllCartsWithToken($token);

        $id = Yii::$app->request->get('id');
        $guest = Guest::getGuestInfo($id);

        return $this->render('bookingcart', [
            'jsonValue' => $jsonValue,
            'price' => $price,
            'guest' => $guest,
            'myCart' => $myCart
        ]);
    }

    public function beforeAction($action) {
        if ($action->id == 'updatecart' || $action->id == 'savecart' || $action->id == 'removesunshadefromcartwithid') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionSavetocart()
    {
        $token = Yii::$app->request->post('token');
        $sunshade_id = Yii::$app->request->post('sunshade_id');
        $sunshade = Yii::$app->request->post('sunshade');
        $previous = Yii::$app->request->post('previous');

        echo  Cart::saveToCart($token, $sunshade_id, $sunshade, $previous);
        return;
    }

    public function actionRemovesunshadefromcartwithid()
    {
        $cartid = Yii::$app->request->post('cartid');
        Cart::findOne($cartid)->delete();
        return;
    }

    public function actionUpdatecart()
    {
        $data = Yii::$app->request->post('data');
        Cart::updateCart($data);
        $token = 'admin-'. $_COOKIE['clientId'];
        $model = General::getFromTo($token);
        $from = $model['searchfrom'];
        $to = $model['searchto'];
        $jsonValue = BookLookup::getAllSunshadesWithinRange($from, $to, $token);
        $myCart = Cart::getAllCartsWithToken($token);
        echo json_encode(['arrayOfSunshades' => $jsonValue, 'myCart' => $myCart]);
        return;
    }

    public function actionReadcart()
    {
        $token = Yii::$app->request->get('token');
        $myCart = Cart::getAllCartsWithToken($token);
        echo json_encode($myCart);
        return;
    }

    public function actionReadsunshades()
    {
        $token = 'admin-' . $_COOKIE['clientId'];
        $model = General::getFromTo($token);
        $from = "";
        $to = "";
        if (!empty($model)) {
            $from = $model['searchfrom'];
            $to = $model['searchto'];
        }
        $jsonValue = BookLookup::getAllSunshadesWithinRange($from, $to, $token);

        echo json_encode($jsonValue);
        return;
    }

    public function actionReadmilestone()
    {
        $token = Yii::$app->request->get('token');
        $cartId = Yii::$app->request->get('cartId');
        $milestones = CartMilestone::getMilestoneWithCartId($cartId);

        echo json_encode($milestones);
        return;
    }

    public function actionAddmilestonetocart()
    {
        $token = 'admin-'.$_COOKIE['clientId'];
        $cartId = Yii::$app->request->post('cartId');
        $arrayOfDates = Yii::$app->request->post('arrayOfDates');
        $arrayOfMoney = Yii::$app->request->post('arrayOfMoney');
        CartMilestone::addMilestonesToCart($token, $cartId, $arrayOfDates, $arrayOfMoney);
        echo json_encode(['ok']);
        return;
    }
    
    public function actionNoprice($seat) {
        return $this->renderPartial('noprice', [
                'seat' => $seat
        ]);
    }

    public function actionSendbookinfo() {
        $guest = BookLookup::getAllBookDetails();
        
        return $this->render('sendbookinfo', [
                'guest' => $guest
            ]);
    }

    public function actionGuestdetail($id){
        $guestId = Yii::$app->request->get('id');
        $details = BookLookup::getAllBookDetailsForGuest($id);

        return $this->render('guestdetail', [
            'guest' => $details,
            'jsonValue' => ($details),
            'id' => $guestId,
        ]);
    }

    public function actionSendemail() {
        $lang = Yii::$app->request->post('lang');
        $id = Yii::$app->request->post('id');
        $guest = BookLookup::getCustomerDetailInfo($id);

        $emailBody = $this->buildEmail($guest);
        if (!empty($guest)) {
            Yii::$app->mailer->compose()
                        ->setFrom(Yii::$app->params['supportEmail'])
                        ->setTo($guest['email'])
                        ->setHtmlBody($emailBody)
                        ->setSubject('Booking info')
                        ->send();
            
            Yii::$app->mailer->compose()
                            ->setFrom(Yii::$app->params['supportEmail'])
                            ->setTo(Yii::$app->params['adminEmail'])
                            ->setHtmlBody($emailBody)
                            ->setSubject('Booking info')
                            ->send();

            $demoEmail = User::findOne(['id' => Yii::$app->user->getId()])->email;
                Yii::$app->mailer->compose()
                            ->setFrom(Yii::$app->params['supportEmail'])
                            ->setTo($demoEmail)
                            ->setHtmlBody($emailBody)
                            ->setSubject('Booking info')
                            ->send();
        }  

         echo "sucess";
         return;
    }

    public function actionSendbulkemail() {
        $id = Yii::$app->request->post('id');
        $guests = BookLookup::getAllBookDetailsForGuest($id);

        $emailBody = $this->buildEmail($guest);
        if (!empty($guests)) {
            foreach ($guests as $key => $guest) {
                Yii::$app->mailer->compose()
                        ->setFrom(Yii::$app->params['supportEmail'])
                        ->setTo($guest['email'])
                        ->setHtmlBody($emailBody)
                        ->setSubject('Booking info')
                        ->send();
            
                Yii::$app->mailer->compose()
                            ->setFrom(Yii::$app->params['supportEmail'])
                            ->setTo(Yii::$app->params['adminEmail'])
                            ->setHtmlBody($emailBody)
                            ->setSubject('Booking info')
                            ->send();

                $demoEmail = User::findOne(['id' => Yii::$app->user->getId()])->email;
                Yii::$app->mailer->compose()
                            ->setFrom(Yii::$app->params['supportEmail'])
                            ->setTo($demoEmail)
                            ->setHtmlBody($emailBody)
                            ->setSubject('Booking info')
                            ->send();
            }
        }

        return $this->redirect(['guestdetail', 'id' => $id, 'lang' => Yii::$app->language]);
    }    


    public function actionPdf() {
        $id = Yii::$app->request->get('id');
        $details = BookLookup::getCustomerDetailInfo($id);

        // get your HTML raw content without any layouts or scripts
        // setup kartik\mpdf\Pdf component
        Yii::$app->response->format = 'pdf';

        // Rotate the page
        Yii::$container->set(Yii::$app->response->formatters['pdf']['class'], [
            'format' => [216, 356], // Legal page size in mm
            'orientation' => 'Landscape', // This value will be used when 'format' is an array only. Skipped when 'format' is empty or is a string
            'beforeRender' => function($mpdf, $data) {},
            ]);

        $this->layout = '//print';
        return $this->renderPartial('guestdetailprintview', ['model' => $details]);
    }

    public function actionPdfall($id) {
         $id = Yii::$app->request->get('id');
        // $guestId = Bookinfo::getGuestId($id);
        $details = BookLookup::getAllBookDetailsForGuest($id);

        // get your HTML raw content without any layouts or scripts
        // setup kartik\mpdf\Pdf component
        Yii::$app->response->format = 'pdf';

        // Rotate the page
        Yii::$container->set(Yii::$app->response->formatters['pdf']['class'], [
            'format' => [216, 356], // Legal page size in mm
            'orientation' => 'Landscape', // This value will be used when 'format' is an array only. Skipped when 'format' is empty or is a string
            'beforeRender' => function($mpdf, $data) {},
            ]);

        $this->layout = '//print';
        return $this->renderPartial('guestallprintview', ['guest' => $details]);
    }

    public function actionDetailview($id){
        $model = BookLookup::getCustomerDetailInfo($id);
        $milestones = Guestmilestone::getMilestonesWithLookupId($id);

        return $this->render('detailview', [
            'model' => $model,
            'milestones' => $milestones
        ]);
    }

    /**
     * Displays a single Book model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
     //   return print_r(Book::getInfoOne($id));
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $guest = new Guest();
        if (Yii::$app->request->post('Guest')){
            $token = 'admin-' . $_COOKIE['clientId'];
            $allSunshades = Cart::getAllValidSunshades($token);
            $totalPaid = CartMilestone::getTotalMoneyFromCart($token);
            $guestId = Guest::saveWithPost(Yii::$app->request->post('Guest'), "Booked", count($allSunshades), $totalPaid);

            for ($i=0; $i < count($allSunshades); $i++) { 
                $paidAmount = CartMilestone::getTotalMoneyWithCartId($allSunshades[$i]['Id']);
                $bookId = Book::saveFromCart($allSunshades[$i], $paidAmount);
                $timestamp = time().$i;
                $milestones = CartMilestone::getMilestoneWithCartId($allSunshades[$i]['Id']);
                Guestmilestone::saveFromCart($bookId, $guestId, $allSunshades[$i]['sunshade'], $milestones, $timestamp);
                Bookinfo::updateInfo(trim($allSunshades[$i]['sunshade']));
                $id = BookLookup::saveBookInfo($allSunshades[$i]['sunshade'], $guestId, $bookId, $timestamp);
                $guest = BookLookup::getCustomerDetailInfo($id);

                $title = ' Made a Reservation on Sunshade ' .  $allSunshades[$i]['sunshade'];
                $title_it = ' Ha prenotato il Parasole ' . $allSunshades[$i]['sunshade'];
                 $username = $guest['username'];

                Notification::saveNewNotification($title, $title_it, $username, $id, trim($allSunshades[$i]['sunshade']));
                Notificationreminder::resetAdminReminder();

                $emailBody = $this->buildEmail($guest);
               Yii::$app->mailer->compose()
                            ->setFrom(Yii::$app->params['supportEmail'])
                            ->setTo($guest['email'])
                            ->setHtmlBody($emailBody)
                            ->setSubject('Booking info')
                            ->send();
                
                Yii::$app->mailer->compose()
                            ->setFrom(Yii::$app->params['supportEmail'])
                            ->setTo(Yii::$app->params['adminEmail'])
                            ->setHtmlBody($emailBody)
                            ->setSubject('Booking info')
                            ->send();
                $demoEmail = User::findOne(['id' => Yii::$app->user->getId()])->email;
                Yii::$app->mailer->compose()
                            ->setFrom(Yii::$app->params['supportEmail'])
                            ->setTo($demoEmail)
                            ->setHtmlBody($emailBody)
                            ->setSubject('Booking info')
                            ->send();
            }
            $this->clearCart();
            return $this->redirect(Url::to(['guest/guestdetail', 'id' => $guestId, 'lang' => Yii::$app->language]));
        }
        return $this->render('create', ['guest' => $guest]);
    }

    public function actionCustomerrecursive()
    {
        $token = 'admin-' . $_COOKIE['clientId'];
        $allSunshades = Cart::getAllValidSunshades($token);
        $guestId = Yii::$app->request->get('id');
        $totalPaid = CartMilestone::getTotalMoneyFromCart($token);
        Guest::increaseRecurringCount($guestId, count($allSunshades), $totalPaid);
        
        for ($i=0; $i < count($allSunshades); $i++) {
            $paidAmount = CartMilestone::getTotalMoneyWithCartId($allSunshades[$i]['Id']);
            $bookId = Book::SaveFromCart($allSunshades[$i], $paidAmount);
            $timestamp = time().$i;
            $milestones = CartMilestone::getMilestoneWithCartId($allSunshades[$i]['Id']);
            Guestmilestone::saveFromCart($bookId, $guestId, $allSunshades[$i]['sunshade'], $milestones, $timestamp);
            Bookinfo::updateInfo(trim($allSunshades[$i]['sunshade']));
            $id = BookLookup::saveBookInfo($allSunshades[$i]['sunshade'], $guestId, $bookId, $timestamp);
            $guest = BookLookup::getCustomerDetailInfo($id);

            $title = ' Made a Reservation on Sunshade ' .  $allSunshades[$i]['sunshade'];
            $title_it = ' Ha prenotato il Parasole ' . $allSunshades[$i]['sunshade'];

            $username = $guest['username'];

            Notification::saveNewNotification($title, $title_it, $username, $id, trim($allSunshades[$i]['sunshade']));
            Notificationreminder::resetAdminReminder();

            $emailBody = $this->buildEmail($guest);
            Yii::$app->mailer->compose()
                        ->setFrom(Yii::$app->params['supportEmail'])
                        ->setTo($guest['email'])
                        ->setHtmlBody($emailBody)
                        ->setSubject('Booking info')
                        ->send();
            
            Yii::$app->mailer->compose()
                            ->setFrom(Yii::$app->params['supportEmail'])
                            ->setTo(Yii::$app->params['adminEmail'])
                            ->setHtmlBody($emailBody)
                            ->setSubject('Booking info')
                            ->send();

            $demoEmail = User::findOne(['id' => Yii::$app->user->getId()])->email;
                Yii::$app->mailer->compose()
                            ->setFrom(Yii::$app->params['supportEmail'])
                            ->setTo($demoEmail)
                            ->setHtmlBody($emailBody)
                            ->setSubject('Booking info')
                            ->send();
        }
        $this->clearCart();
        return $this->redirect(Url::to(['guest/guestdetail', 'id' => $guestId, 'lang' => Yii::$app->language]));
    }

    public function actionCheckcustomerexistence() {
        $email = Yii::$app->request->get('email');

        $guestId = Guest::checkCustomerExistence($email);

       echo isset($guestId['Id']) ?  1 :  0;
        return;
    }

    public function actionDeletebooking() {
        $lookupIds  = (array)Yii::$app->request->post('lookupIds');
        $lookupIds = implode(',', $lookupIds);

        Bookinfo::updateBookInfoWithArray($lookupIds);
        Notification::eraseNotification($lookupIds);
        BookLookup::deleteLookup($lookupIds);

        $bookIds = (array)Yii::$app->request->post('bookIds');
        $bookIds = implode(',', $bookIds);
        Book::deleteBook($bookIds);
        $guestIds = (array)Yii::$app->request->post('guestIds');
        $paidPrices = (array)Yii::$app->request->post('paidPrices');

        for ($i=0; $i < count($guestIds); $i++) {
            Guest::decreaseInfo($guestIds[$i], count($lookupIds), $paidPrices[$i]);
        }
       
        echo "1";
        return;
    }

    /**
     * Finds the Guest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Guest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Guest::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
