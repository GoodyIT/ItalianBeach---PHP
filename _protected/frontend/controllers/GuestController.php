<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Guest;
use frontend\models\GuestSearch;
use frontend\models\Bookinfo;
use frontend\models\Book;
use frontend\models\Notification;
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
     * Lists all Guest models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GuestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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
    
     public function unsetCookies() {
        $cookies = Yii::$app->response->cookies;
        
        $cookies->remove('sunshadeseat');
        unset($cookies['arrival']);
        unset($cookies['price']);
        unset($cookies['mainprice']);
        unset($cookies['supplement']);
        unset($cookies['servicetype']);
        unset($cookies['guests']);
    }
    
    public function actionAftersuccess() {
        $bookId = Book::saveInfo();
        $model =  Yii::$app->session->getFlash("guestmodel");
        $guestId = Guest::saveInfo($model);
        unset($_model);

        $cookies = Yii::$app->request->cookies;
        if (($cookie = $cookies->get('sunshadeseat')) !== null) {
            $sunshadeseat = $cookie->value;
        }

        Bookinfo::updateInfo($sunshadeseat, $guestId, $bookId, "booked");
        $this->unsetCookies();
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
        $cookies = Yii::$app->request->cookies;
        if (($cookie = $cookies->get('sunshadeseat')) !== null){ 
            $sunshadeseat = $cookie->value;
        }
        
        if (!isset($sunshadeseat) || $sunshadeseat == "") {
            return $this->redirect(['site/index']);
        }
    
        $model = new Guest();
        
        if ($model->load(Yii::$app->request->post())) {
           // $guestId = $model->saveOrUpdateInfo();
            $model->saveInfoIntoCookie();
            return $this->redirect(Url::to(['confirm']));
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionConfirm()
    {
        /* @var $_model type */
        $cookies = Yii::$app->request->cookies;
        $price = 0;
        $servicetype = 1;
        $payment = "paypal";
        if (($cookie = $cookies->get('price')) !== null) { 
            $price = $cookie->value;  
        }
        
        if (($cookie = $cookies->get('servicetype')) !== null) {
            $servicetype = $cookie->value;
            $servicetype = Servicetype::find()->where(['id' => $servicetype])->one();
        }
    
        return $this->render('bookconfirm', [
            'amount' => $price,
            'description' => $servicetype['servicename'],
            'payment' => $payment,
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

                } else if($book['payment_method'] == 'paypal') {
                    $baseUrl = Yii::$app->urlManager->createAbsoluteUrl(['guest/bookcomplete']);
                    $payment =  Yii::$app->paypal->makePaymentUsingPayPal($book['amount'], 'EUR', $book['description'], $baseUrl."?success=true", $baseUrl . "?success=false");

                    // Guest::updateBookInfo($guestId, $payment->getId(), $payment->getState());

                    //Guest::savePaymentInfoIntoCookie($payment->getId(), $payment->getState());
                    
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

        $messageType = "";
        $message = "";
        if ($success) {
            try {
               $payment = Yii::$app->paypal->executePayment($_GET['paymentId'], $_GET['PayerID']);
                

               if ($payment->getState() == "approved") {
                    $cookies = Yii::$app->request->cookies;
                    $sunshadeseat = "";
                    $price = "";
                    if (($cookie = $cookies->get('sunshadeseat')) !== null) {
                        $sunshadeseat = $cookie->value;
                    }

                     if (($cookie = $cookies->get('price')) !== null) { 
                        $price = $cookie->value;  
                    }

                    $guestId = Guest::saveOrUpdateInfo();
                    $book = Book::saveInfo();

                    $timestamp = time();

                    Bookinfo::updateInfo($sunshadeseat, $guestId, $book->Id, "booked", $timestamp);

                    $title = ' Made a Reservation on Sunshade ' .  $sunshadeseat;
                    $title_it = ' Ha prenotato il Parasole ' . $sunshadeseat;
                    Notification::saveNewNotification($title, $title_it, $sunshadeseat);

                    $guest = Bookinfo::getCurrentBookinfo($sunshadeseat);

                    $timestamp = time();
                    Guestmilestone::saveWithGuestId([$book->price], [$book->bookedtime], $guestId, $sunshadeseat, $timestamp);

                    $emailBody = $this->buildEmailBody($guest);
                    $message = Yii::$app->mailer->compose()
                        ->setFrom(Yii::$app->params['supportEmail'])
                        ->setTo($guest['email'])
                        ->setSubject('Booking info')
                        ->setHtmlBody($emailBody)
                        ->send();

                    $message = Yii::$app->mailer->compose()
                        ->setFrom(Yii::$app->params['supportEmail'])
                        ->setTo(Yii::$app->params['supportEmail'])
                        ->setSubject('Booking info')
                        ->setHtmlBody($emailBody)
                        ->send();

                    $this->unsetCookies();
                    $messageType = "success";
                    $message = Yii::t('messages', "Your payment was successful. Your book id is ") . $book['Id'];
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
        }

        Yii::$app->session->setFlash($messageType, $message);

        return $this->redirect(Url::to(['site/index', 'messageType' => $messageType, 'message' => $message]));
    }
    
    public function actionOrdercomplete($orderId = 1, $success=true) {
        return $this->render('bookplace');
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
