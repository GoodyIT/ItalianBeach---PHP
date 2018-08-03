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
                        'actions' => ['index', 'view', 'create', 'logout', 'noprice', 'roombook', 'checkbulkbookavailability', 'saveintosession', ],
                        'allow' => true,
                        'roles' => ['admin'],
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

    public function actionNoprice($seat) {
        return $this->renderPartial('noprice', [
                'seat' => $seat
        ]);
    }

    public function actionCheckbulkbookavailability(){
        $selection= (array)Yii::$app->request->post('selection');
        $selection = implode(',', $selection);
        /*print_r($selection);
        return;*/
        $_allSunshades = Bookinfo::getAllSunshades($selection);
        $allSunshades = array();
        foreach ($_allSunshades as $key => $value) {
            array_push($allSunshades, $value['seat']);
        }

        $rows = Bookinfo::getSunshadeRows($selection);
        if (intval($selection[0]) > 167)  {
            $rows = Bookinfo::getRooms($selection);
        }

        if (count($rows) == 1) {
            echo "1";
            return;
        }

        $priceLists = Price::getAllInfo();

        $newPriceLists = array();

        if (count($rows) > 1) {
            if (intval($rows[0][0]) != "1") {
                for ($i = 1; $i <= 4; $i++) { 
                    $serviceExist = true;
                    foreach ($rows as $key => $value) {
                        if (!isset($priceLists[$value][$i])) {
                            $serviceExist = false;
                        }
                    }
                    if ($serviceExist) {
                        foreach ($rows as $key => $value) {
                            $newPriceLists[$value][$i] =  $priceLists[$value][$i];
                        }
                    }
                }
            } else {
                $serviceExist = true;
                foreach ($rows as $key => $value) {
                    if (!isset($priceLists[$value][5])) {
                        $serviceExist = false;
                    }
                }
                if ($serviceExist) {
                    foreach ($rows as $key => $value) {
                        $newPriceLists[$value][5] =  $priceLists[$value][5];
                    }
                }
            }
        } 

        if (count($newPriceLists) > 0) {
            echo "1";
        } else {
            echo "0";
        }
        return;
    }

    /**
     * Lists all Bookinfo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BookinfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $session = Yii::$app->session;
        if (!$session->isActive) $session->open();
        $selection = $session['selection'];

        $session->close();

        return $this->render('bookinfo', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'selection' => $selection,
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

            Guestmilestone::saveWithGuestId($array_price, $array_date, $bookId, $guestId, $sunshadeseat, $timestamp);
            BookLookup::updateTimestamp($sunshadeseat, $bookId, $guestId, $timestamp);

            if (!$milestoneCompleteState && array_sum($array_price) > 0)
            {
                $title = ' Update Milestones on Sunshade ' .  $sunshadeseat;
                $title_it = ' Ha Aggiornato gli Acconti sul Parasole ' . $sunshadeseat;

                Notification::saveNewNotification($title, $title_it, $sunshadeseat);
                Book::updateInfoWithBookId($bookId, $originPaidprice, $array_price);
            }
        }

        $book = Book::getInfoWithId($model->bookId);
        $guest= Guest::findOne($model->guestId);
        $milestones = Guestmilestone::getMilestonesWithGuestId($model->guestId, $model->seat);

        return $this->render('view', [
            'model' => $model,
            'guest' => $q,
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
