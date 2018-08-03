<?php
namespace frontend\controllers;

use common\models\LoginForm;
use frontend\models\Bookinfo;
use frontend\models\BookLookup;
use frontend\models\Cart;
use frontend\models\General;
use frontend\models\Price;
use frontend\models\Guest;
use frontend\models\Guestmilestone;
use frontend\models\CartMilestone;
use frontend\models\PendingGuest;
use frontend\models\Notification;
use frontend\models\Notificationreminder;
use frontend\models\ContactForm;
use common\models\User;
use frontend\models\Setting;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Yii;

/**
 * Site controller.
 * It is responsible for displaying static pages, logging users in and out,
 * sign up and account activation, password reset.
 */
class SiteController extends \common\component\BaseController
{
    /**
     * Returns a list of behaviors that this component should behave as.
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'about', 'contact'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Declares external actions for the controller.
     *
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

//------------------------------------------------------------------------------------------------//
// STATIC PAGES
//------------------------------------------------------------------------------------------------//

    /**
     * Displays the index (home) page.
     * Use it in case your home page contains static content.
     *
     * @return string
     */
    public function actionTest()
    {
        $lang = Yii::$app->language;
        
        return $this->render('index');
      
    }

    public function actionIndex()
    {
        //   Bookinfo::resetBookInfo();
        $from = Yii::$app->request->get('from');
        $to = Yii::$app->request->get('to');
        $token = isset($_COOKIE['clientId']) ? $_COOKIE['clientId'] : "";
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

        // check and update sunshade which is expired
        if (!Notificationreminder::checkSunshadeReminder()) {
            BookLookup::checkExpiredate();
            Notificationreminder::updateSunshadeReminder();
        }

        if (!Notificationreminder::checkCartExpire()) {
            Cart::checkExpire();
            CartMilestone::checkExpire();
            Notificationreminder::updateCartExipreReminder();
        }

        return $this->render('test', [
            'jsonValue' => $jsonValue,
            'from' => $from,
            'to' => $to
        ]);
    }

    public function actionGotocart()
    {
        if (!isset($_COOKIE['clientId'])) {
            return $this->redirect(Url::to(['site/index', 'lang' =>  Yii::$app->language]));
        }
        $from = Yii::$app->request->get('from');
        $to = Yii::$app->request->get('to');
        $token = $_COOKIE['clientId'];
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

        return $this->render('bookingcart', [
            'jsonValue' => $jsonValue,
            'price' => $price,
            'myCart' => $myCart,
            'from' => $from,
            'to' => $to
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

        $token = $_COOKIE['clientId'];
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
        $token = $_COOKIE['clientId'];
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

    public function actionPrice()
    {
        $lang = Yii::$app->request->get('lang');
        $prices = Price::getAllAsArray($lang);
        return $this->render('price', [
            'prices' => $prices,
        ]);
    }

    public function actionInfo()
    {
        return  $this->render('info');
    }

    /**
     * Displays the about static page.
     *
     * @return string
     */

    public function actionAbout()
    {
        $settingInfo = Setting::findOne(1);
        return $this->render('about', [
            'settingInfo' => $settingInfo,
        ]);
    }

    /**
     * Displays the contact static page and sends the contact email.
     *
     * @return string|\yii\web\Response
     */
    public function actionContact()
    {
        $model = new ContactForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) 
        {
            if ($model->contact(Yii::$app->params['adminEmail'])) 
            {
                Yii::$app->session->setFlash('success', 
                    'Thank you for contacting us. We will respond to you as soon as possible.');
            } 
            else 
            {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } 
        
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

//------------------------------------------------------------------------------------------------//
// LOG IN / LOG OUT / PASSWORD RESET
//------------------------------------------------------------------------------------------------//

    /**
     * Logs in the user if his account is activated,
     * if not, displays appropriate message.
     *
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) 
        {
            return $this->goHome();
        }

        // get setting value for 'Login With Email'
        $lwe = Yii::$app->params['lwe'];

        // if 'lwe' value is 'true' we instantiate LoginForm in 'lwe' scenario
        $model = $lwe ? new LoginForm(['scenario' => 'lwe']) : new LoginForm();

        // now we can try to log in the user
        if ($model->load(Yii::$app->request->post()) && $model->login()) 
        {
            return $this->goBack();
        }
        // user couldn't be logged in, because he has not activated his account
        elseif($model->notActivated())
        {
            // if his account is not activated, he will have to activate it first
            Yii::$app->session->setFlash('error', 
                'You have to activate your account first. Please check your email.');

            return $this->refresh();
        }    
        // account is activated, but some other errors have happened
        else
        {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the user.
     *
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
