<?php

namespace backend\controllers;

use Yii;
use frontend\models\Guest;
use frontend\models\Bookinfo;
use frontend\models\Book;
use common\models\User;
use frontend\models\GuestSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ArrayDataProvider;
use yii\data\Sort;
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
                        'actions' => ['sendbookinfo', 'pdf', 'pdfall', 'guestdetail', 'sendemail', 'sendbulkemail', 'bookinfo','view', 'update', 'guestinfo', 'detailview', 'guestalldetail', 'logout', 'delete', 'deletebooking', 'checkcustomerexistence'],
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

    public function actionGuestinfo() {
        $guest = Guest::getAllInfo();

        $availableSunshadePair = Bookinfo::getAvailableSunshadePair();

        $sunshadeList = Bookinfo::getSunshadeList();
        // print_r($sunshadeList);
        // return;
        return $this->render('guestinfo', [
            'guest' => $guest,
            'sunshadeList' => $sunshadeList,
            'availableSunshadePair' => $availableSunshadePair
        ]);
    }

    public function actionSendbookinfo($lang) {
        $guest = Bookinfo::getAllBookInfo();
        
        return $this->render('sendbookinfo', [
                'guest' => $guest
            ]);
    }

    public function actionGuestalldetail($id) {
        $details = Bookinfo::getAllBookDetails($id);

        return $this->render('guestalldetail', [
            'guest' => $details,
            'guestId' => $id,
            'details' => $details,
        ]);
    }

    public function actionGuestdetail($id){
        $id = Yii::$app->request->get('id');
        $guestId = Bookinfo::getGuestId($id);
        $details = Bookinfo::getAllBookDetails($guestId['guestId']);


        return $this->render('guestdetail', [
            'guest' => $details,
            'jsonValue' => json_encode($details),
            'id' => $id,
            'details' => $details,
        ]);
    }

    public function actionSendemail() {
        $id = Yii::$app->request->post('id');
        $guestId = Bookinfo::getGuestId($id);
        $lang = Yii::$app->request->post('lang');
        $details = Guest::getGuestDetail($guestId['guestId'], $id);

        if ($details) {
           $emailBody =  $this->buildEmailBody($details, $lang);

            Yii::$app->mailer->compose()
                    ->setFrom(Yii::$app->params['supportEmail'])
                    ->setTo($details['email'])
                    ->setSubject('Booking info')
                    ->setHtmlBody($emailBody)
                    ->send();
            
            $adminEmails = User::getAllEmails();
            foreach ($adminEmails as $key => $value) {        
                Yii::$app->mailer->compose()
                    ->setFrom(Yii::$app->params['supportEmail'])
                    ->setTo($value['email'])
                    ->setSubject('Booking info')
                    ->setHtmlBody($emailBody)
                    ->send();
            }
        }

         echo "sucess";
         return;

       //  return $this->redirect(['guestdetail', 'id' => $guestId]);
    }

    public function actionSendbulkemail() {
        $id = Yii::$app->request->post('id');
        $guestId = Bookinfo::getGuestId($id);

        $lang = Yii::$app->request->post('lang');
        $details = Bookinfo::getAllBookDetails($guestId['guestId']);

        if ($details) {
            $emailBody = array();
            for ($i = 0; $i < count($details); $i++) {
               $emailBody[] =  $this->buildEmailBody($details[$i], $lang);
            }

            Yii::$app->mailer->compose()
                    ->setFrom(Yii::$app->params['supportEmail'])
                    ->setTo($details[0]['email'])
                    ->setSubject('Booking info')
                    ->setHtmlBody(implode(" <br>", $emailBody))
                    ->send();
            
            $adminEmails = User::getAllEmails();
            foreach ($adminEmails as $key => $value) {        
                Yii::$app->mailer->compose()
                    ->setFrom(Yii::$app->params['supportEmail'])
                    ->setTo($value['email'])
                    ->setSubject('Booking info')
                    ->setHtmlBody(implode("<br>", $emailBody))
                    ->send();
            }
        }

        return $this->redirect(['guestdetail', 'id' => $id, 'lang' => Yii::$app->language]);
    }    


    public function actionPdf() {
        $id = Yii::$app->request->get('id');

        $guestId = Bookinfo::getGuestId($id);

        $details = Guest::getGuestDetail($guestId['guestId'], $id);

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
        $guestId = Bookinfo::getGuestId($id);
        $details = Bookinfo::getAllBookDetails($guestId['guestId']);

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
         $guestId = Bookinfo::getGuestId($id);

         $guest = Guest::getGuestDetail($guestId['guestId'], $id);

        return $this->render('detailview', [
                'model' => $guest,
                'jsonValue' => json_encode($guest),
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

    public function actionCheckcustomerexistence() {
        $email = Yii::$app->request->get('email');

        $guestId = Guest::checkCustomerExistence($email);

        echo isset($guestId['Id']) && $guestId['Id'] != -1;
        return;
    }

    public function actionUpdate($id)
    {
        $id = Yii::$app->request->get('id');
        $guestId = Bookinfo::getGuestId($id);
        $model = $this->findModel($guestId['guestId']);

        if (Yii::$app->request->post('Guest')) {
            // print_r(Yii::$app->request->post('Guest'));
            // return;
            Guest::saveUpdateInfo(Yii::$app->request->post('Guest'));
            $this->refresh();
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete()
    {
        $id  = Yii::$app->request->post('id');
        $this->findModel($id)->delete();

        Bookinfo::resetBookInfoWithGuestId($id);

       echo $id;
        return;
    }

    public function actionDeletebooking() {
        $selection  = (array)Yii::$app->request->post('selection');

        print_r($selection);
      //  Bookinfo::resetBookInfoWithArray($selection);
       // echo implode(',', $selection);
        foreach ($selection as $key => $value) {
            Bookinfo::resetBookInfoWithId($value);
        }
        
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
