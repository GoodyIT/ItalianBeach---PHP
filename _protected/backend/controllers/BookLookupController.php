<?php

namespace backend\controllers;

use Yii;
use frontend\models\Bookinfo;
use frontend\models\BookLookup;
use frontend\models\Book;
use frontend\models\Guestmilestone;
use frontend\models\Guest;
use frontend\models\GuestSearch;
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
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BookLookupController implements the CRUD actions for Guest model.
 */
class BookLookupController extends \common\component\BaseController
{
    /**
     * @inheritdoc
     */
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
    public function actionView()
    {
        $booklookupId = Yii::$app->request->get('booklookupId');
        $sunshadeId = Yii::$app->request->get('sunshadeId');
        $guestId = Yii::$app->request->get('guestId');
        $bookId = Yii::$app->request->get('bookId');
        $model = Bookinfo::findOne($sunshadeId);
        $book = Book::getInfoWithId($bookId);
        $guest= Guest::findOne($guestId);

        if (Yii::$app->request->post('Milestone_Price')) {
            $array_price = (array)Yii::$app->request->post('Milestone_Price');
            $array_date = (array)Yii::$app->request->post('Milestone_Date');
            $timestamp = Yii::$app->request->post('timestamp');
            $sunshade = Yii::$app->request->post('sunshadeseat');
            $price = Yii::$app->request->post('price');
            $originPaidprice = Yii::$app->request->post('paidprice'); 
            $milestoneCompleteState = Yii::$app->request->post('milestoneCompleteState');

            if (array_sum($array_price) > 0)
            {
                $username = $guest['firstname'] . ' ' . $guest['lastname'];

                $title = ' Update Milestones ' .  $sunshade;
                $title_it = ' Aggiorna Acconti ' . $sunshade;

                 Guestmilestone::saveWithGuestId($array_price, $array_date, $bookId, $guestId, $model->seat, $timestamp);
                Notification::saveNewNotification($title, $title_it, $username, $booklookupId,  trim($sunshade));
                Book::updateInfoWithBookId($bookId, $originPaidprice, $array_price);
                Guest::increaseRecurringCount($guestId, 0, array_sum($array_price));
            }
            return $this->redirect(['view',
                'booklookupId' => $booklookupId,
                'sunshadeId' => $sunshadeId,
                'guestId' => $guestId,
                'bookId' => $bookId]);
        }

        $milestones = Guestmilestone::getMilestonesWithLookupId($booklookupId);

        $timestamp;
        if (!empty($milestones)) {
            $timestamp = $milestones[0]['timestamp'];
        } else {
            $timestamp = time();
            BookLookup::updateTimestamp($model->seat, $bookId, $guestId, $timestamp);
        }

        $milestoneCompleteState = true;
        if(intval($book['paidprice']) < intval($book['price'])) {
            $milestoneCompleteState = false;  
        } 

        return $this->render('view', [
            'timestamp' => $timestamp,
            'milestoneCompleteState' => $milestoneCompleteState,
            'model' => $model,
            'guest' => $guest,
            'book' => $book,
            'sunshadeId' => $sunshadeId,
            'bookId' => $bookId,
            'guestId' => $guestId,
            'booklookupId' => $booklookupId,
            'milestones' => $milestones
        ]);
    }
}
