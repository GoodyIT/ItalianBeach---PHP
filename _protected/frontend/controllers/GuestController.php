<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Guest;
use frontend\models\PendingGuest;
use frontend\models\GuestSearch;
use frontend\models\Bookinfo;
use frontend\models\Cart;
use frontend\models\CartMilestone;
use frontend\models\General;
use frontend\models\BookLookup;
use frontend\models\Book;
use common\models\User;
use frontend\models\Price;
use frontend\models\Pricesearch;
use frontend\models\Notification;
use frontend\models\Notificationreminder;
use frontend\models\Guestmilestone;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use frontend\models\Servicetype;

/**
 * GuestController implements the CRUD actions for Guest model.
 */
class GuestController extends \common\component\BaseController
{
    /**
     * @inheritdoc
     */
    public $showBookList = false;
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Displays a single Guest model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    
    public function clearCart() {
        $token = $_COOKIE['clientId'];
        Cart::clearCart($token);
        CartMilestone::deleteMilestones($token);
        General::resetFromTo($token);
        PendingGuest::clearGuest($token);
    }
    
    public function actionCheckcustomerexistence() {
        $email = Yii::$app->request->get('email');

        $guestId = Guest::checkCustomerExistence($email);

        echo isset($guestId['Id']) && $guestId != 1;
        return;
    }

    /**
     * Creates a new Guest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (!isset($_COOKIE['clientId'])) {
            return $this->redirect(Url::to(['site/index', 'lang' =>  Yii::$app->language]));
        }
        $model = new PendingGuest();
        $price = Price::getAllInfoWithArray();
        $token = $_COOKIE['clientId'];
        $myCart = Cart::getAllValidSunshades($token);
        $totalPrice = Cart::getTotalPriceOfCart($token);

        if ($model->load(Yii::$app->request->post())) {
            $model->saveInfo();
            $lang = Yii::$app->request->get('lang');
            return $this->redirect(Url::to(['confirm', 'lang' =>  $lang]));
        } else {
            return $this->render('guestinfo', [
                'model' => $model,
                'token' => $token,
                'price' => $price,
                'myCart' => $myCart,
                'totalPrice' => $totalPrice
            ]);
        }
    }
    
    public function actionConfirm()
    {
        if (!isset($_COOKIE['clientId'])) {
            return $this->redirect(Url::to(['site/index', 'lang' =>  Yii::$app->language]));
        }
        $token = $_COOKIE['clientId'];
        $price = Price::getAllInfoWithArray();
        $myCart = Cart::getAllValidSunshades($token);
        $totalPrice = Cart::getTotalPriceOfCart($token);

        return $this->render('bookconfirm', [
            'price' => $price,
            'myCart' => $myCart,
            'totalPrice' => $totalPrice
        ]);
    }

    public function getLink(array $links, $type) {
        foreach($links as $link) {
            if($link->getRel() == $type) {
                return $link->getHref();
            }
        }
        return "";
    }
    
    public function actionBookplace() {
       if($_SERVER['REQUEST_METHOD'] == 'POST') {	
            $book = $_REQUEST['book'];
            $session = Yii::$app->session;
            $session['language'] = Yii::$app->request->post('lang');
            $session['token'] = $_COOKIE['clientId'];
            $message = "";
            try {
                if($book['payment_method'] == 'credit_card') {
                    // Make a payment using credit card.
                    $user = getUser(getSignedInUser());
                    $payment = makePaymentUsingCC($user['creditcard_id'], $book['amount'], 'USD', $book['description']);
                    $orderId = addOrder(getSignedInUser(), $payment->getId(), $payment->getState(),
                            $book['amount'], $book['description']);
                    $message = "Your order has been placed successfully. Your Order id is <b>$orderId</b>";
                    $messageType = "success";

                } else if($book['payment_method'] == 'PayPal') {
                    $baseUrl = Yii::$app->urlManager->createAbsoluteUrl(['guest/bookcomplete']);

                    $payment =  Yii::$app->paypal->makePaymentUsingPayPal($book['amount'], 'EUR', $book['description'], $baseUrl."?success=true", $baseUrl . "?success=false");
                    header("Location: " . $this->getLink($payment->getLinks(), "approval_url") );
                   exit;
                }
            } catch (\PayPal\Exception\PPConnectionException $ex) {
                    $message = parseApiError($ex->getData());
                    $messageType = "error";
            } catch (Exception $ex) {
                    $message = $ex->getMessage();
                    $messageType = "error";
            }
        }
    }

    public function actionBookcomplete(){
        $success = Yii::$app->request->get("success");
        $session = Yii::$app->session;
        $lang =  $session['language'];
        Yii::$app->language = $session['language'];
        $token = $session['token'];
        $cartId = 0;

        $messageType = "";
        $message = "";
        if ($success == 'true') {
            try {
                $payment = Yii::$app->paypal->executePayment($_GET['paymentId'], $_GET['PayerID']);

                if ($payment->getState() == "approved") {
                    $allSunshades = Cart::getAllValidSunshades($token);
                    $pendingGuest = PendingGuest::getGuestInfo($token);
                    if (empty($allSunshades)) {
                        $messageType = "error";
                        $message = Yii::t('messages', "Sorry, Your booking cart is empty now.");
                        Yii::$app->session->setFlash($messageType, $message);
                        return $this->redirect(Url::to(['site/index', 'lang' => $lang]));
                    } else if (empty($pendingGuest['email'])) {
                        $messageType = "error";
                        $message = Yii::t('messages', "Sorry, Please input your information here.");
                        Yii::$app->session->setFlash($messageType, $message);
                        return $this->redirect(Url::to(['guest/create', 'lang' => $lang]));
                    }  
                    $totalPaid = 0;
                    foreach ($allSunshades as $item) {
                        $totalPaid += $item['price'];
                    }
                    $guestId = Guest::saveOrUpdateInfo($pendingGuest, count($allSunshades), $totalPaid);
                    $book = Book::saveInfo($allSunshades);
                    for ($i = 0; $i < count($allSunshades); $i++) {
                        $timestamp = time().$i;
                        Bookinfo::updateInfo($allSunshades[$i]['sunshade']);
                        $id = BookLookup::saveBookInfo($allSunshades[$i]['sunshade'], $guestId, $book[$i]->Id, $timestamp);

                        $title = ' Made a Reservation on Sunshade ' .  $allSunshades[$i]['sunshade'];
                        $title_it = ' Ha prenotato il Parasole ' . $allSunshades[$i]['sunshade'];
                        $guest = BookLookup::getCustomerDetailInfo($id);
                        $username = $guest['username'];

                        Notification::saveNewNotification($title, $title_it, $username, $id, trim($allSunshades[$i]['sunshade']));
                        Notificationreminder::resetAdminReminder();
                        $milestones = CartMilestone::getMilestoneWithCartId($allSunshades[$i]['Id']);
                       Guestmilestone::saveFromCart($book[$i]->Id, $guestId, $allSunshades[$i],  $milestones, $timestamp);
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
                    }
                   
                   $messageType = "success";
                   $message = Yii::t('messages', "Your payment was successful.");
                    if ($lang == "it") {
                      $message = "Il tuo pagamento è stato eseguito correttamente.";
                    }
               }
            } catch (\PayPal\Exception\PPConnectionException $ex) {
                $message = parseApiError($ex->getData());
                $messageType = "error";
            } catch (Exception $ex) {
                $message = $ex->getMessage();
                $messageType = "error";
            }
        }
        else {
            $messageType = "error";
            $message = Yii::t('messages', "Your payment was cancelled.");
            if ($lang == "it") {
               $message = "Il pagamento è stato annullato.";
            }
        }

        Yii::$app->session->setFlash($messageType, $message);

        $this->clearCart();

       return $this->redirect(Url::to(['site/index', 'lang' => $lang]));
    }
    
    public function actionSuccess(){
        return $this->render('success');
    }
    
    /**
     * Updates an existing Guest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->guestid]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Guest model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
