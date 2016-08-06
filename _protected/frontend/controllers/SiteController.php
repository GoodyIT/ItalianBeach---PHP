<?php
namespace frontend\controllers;

use common\models\LoginForm;
use frontend\models\Bookinfo;
use frontend\models\Rowrestriction;
use frontend\models\Price;
use frontend\models\Servicetype;
use frontend\models\Notificationreminder;
use frontend\models\ContactForm;
use frontend\models\Setting;
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
    public function actionIndex()
    {
        $day = Yii::$app->request->get('day');
        Bookinfo::resetBookInfo();
        $message = Yii::$app->request->get('message');
        $messageType = Yii::$app->request->get('messageType');

        $jsonValue = Bookinfo::getAllInfoForFrontend($day);

        // check and update sunshade which is expired

        if (!Notificationreminder::checkSunshadeReminder()) {
            Bookinfo::checkExpiredate();
            Notificationreminder::updateSunshadeReminder();
        }

        return $this->render('index', [
            'jsonValue' => $jsonValue,
            'message' => $message,
            'messageType' => $messageType,
            'day' => $day,
        ]);
    }


    public function actionInfo()
    {
        return  $this->render('info');
    }

    /**
     * Display the book list of the sunshade
     */
    public function actionSunshade($id){
        $day = Yii::$app->request->get('day');
        $lang = Yii::$app->request->get('lang');
        $rowRestrictionLists = Price::getRowristriction();
        $priceLists = Price::getAllInfo();
        $serviceTypeLists = Servicetype::getAllInfo($lang);

        Bookinfo::updateSeatState($id, "booking");

        return $this->render('booklist', [
            'day' =>$day,
            'model' => $id,
            'rowRestrictionLists' => $rowRestrictionLists,
            'priceLists' => $priceLists,
            'serviceTypeLists' => $serviceTypeLists,
        ]);
    }

    public function actionBook() {
         $availability = Bookinfo::checkAvailability(Yii::$app->request->post('sunshadeseat'), Yii::$app->request->post('day'));

        if (!$availability)
            $this->refresh();

        $cookies = Yii::$app->response->cookies;

        // add a new cookie to the response to be sent
        $cookies->add(new \yii\web\Cookie([
            'name' => 'sunshadeseat',
            'value' => Yii::$app->request->post('sunshadeseat'),
        ]));

        $cookies->add(new \yii\web\Cookie([
            'name' => 'arrival',
            'value' => Yii::$app->request->post('arrival'),
        ]));

        $cookies->add(new \yii\web\Cookie([
            'name' => 'servicetype',
            'value' => Yii::$app->request->post('servicetype'),
        ]));

        $cookies->add(new \yii\web\Cookie([
            'name' => 'price',
            'value' =>Yii::$app->request->post('price'),
        ]));

        $cookies->add(new \yii\web\Cookie([
            'name' => 'guests',
            'value' => Yii::$app->request->post('guests'),
        ]));

        $cookies->add(new \yii\web\Cookie([
            'name' => 'mainprice',
            'value' => Yii::$app->request->post('mainprice'),
        ]));

        $cookies->add(new \yii\web\Cookie([
            'name' => 'tax',
            'value' => Yii::$app->request->post('tax'),
        ]));

        $cookies->add(new \yii\web\Cookie([
            'name' => 'supplement',
            'value' => Yii::$app->request->post('supplement'),
        ]));

        Bookinfo::updateSeatState(Yii::$app->request->post('sunshadeseat'), "booking");

        Yii::$app->language =  Yii::$app->request->post('lang');
       /*header("Location: " . Yii::$app->urlManager->createAbsoluteUrl(["guest/create", "lang"=>Yii::$app->request->post('lang')]));*/
        return $this->redirect(\Yii::$app->urlManager->createUrl("guest/create"));
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
