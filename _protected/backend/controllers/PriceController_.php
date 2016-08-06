<?php

namespace backend\controllers;

use Yii;
use frontend\models\Price;
use frontend\models\Pricesearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\component\BaseController;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\filters\AccessControl;
use frontend\models\Rowrestiction;

/**
 * PriceController implements the CRUD actions for Price model.
 */
class PriceController extends BaseController
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
                        'actions' => ['index', 'update', 'create', 'view', 'logout'],
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

    /**
     * Lists all Price models.
     * @return mixed
     */
    public function actionIndex()
    {
       $searchModel = new Pricesearch();
     //$priceLists = Price::getAllInfo();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

     /*   $priceLists = Price::getInfo();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $priceLists,            
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                    'attributes' => ['rowid', 'servicename', 'mainprice', 'tax', 'supplement', 'maxguests'],
            ],
            'key' => 'Id',
        ]);
       */ 
    
        return $this->render('index', [
          //  'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDelete($id)
    {
       $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Displays a single Price model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' =>Price::getInfo($id),
        ]);
    }

    /**
     * Updates an existing Price model.
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
            return $this->redirect(['view', 'id' => $model->Id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionCreate()
    {
        $model = new Price();
        
        if ($model->load(Yii::$app->request->post('Price'))) {
            $model->save(false);
            Rowrestiction::addServicetype($model->rowId, $model->servicetype_Id);
            return $this->redirect(['view', 'id' => $model->Id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the Price model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Price the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Price::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
