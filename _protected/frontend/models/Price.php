<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tbl_price".
 *
 * @property integer $Id
 * @property string $rowid
 * @property integer $servicetype_Id
 * @property integer $mainprice
 * @property integer $tax
 * @property integer $supplement
 * @property integer $maxguests
 *
 * @property TblServicetype $servicetype
 */
class Price extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Id', 'rowid', 'mainprice', 'tax', 'supplement', 'maxguests'], 'required'],
            [['Id', 'servicetype_Id', 'mainprice', 'tax', 'supplement', 'maxguests'], 'integer'],
            [['rowid'], 'string', 'max' => 100],
            [['servicetype_Id'], 'exist', 'skipOnError' => true, 'targetClass' => Servicetype::className(), 'targetAttribute' => ['servicetype_Id' => 'Id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => Yii::t('messages', 'ID'),
            'rowid' => Yii::t('messages', 'Sunshades Row'),
            'servicetype_Id' => Yii::t('messages', 'Service Type  ID'),
            'mainprice' => Yii::t('messages', 'Main Price'),
            'tax' => Yii::t('messages', 'Add.Pass'),
            'supplement' => Yii::t('messages', 'Supplement'),
            'maxguests' => Yii::t('messages', 'Max Guests'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicetype()
    {
        return $this->hasOne(Servicetype::className(), ['Id' => 'servicetype_Id']);
    }
    
    public static function getAllInfo() {
        $sql = "SELECT * FROM tbl_price";
        $lists =  static::findBySql($sql)->asArray()->all();
        $results = [];
        foreach ($lists as $key => $value) {
            $results[$value['rowid']][$value['servicetype_Id']]['mainprice'] = $value['mainprice'];
            $results[$value['rowid']][$value['servicetype_Id']]['tax'] = $value['tax'];
            $results[$value['rowid']][$value['servicetype_Id']]['supplement'] = $value['supplement'];
            $results[$value['rowid']][$value['servicetype_Id']]['maxguests'] = $value['maxguests'];
            $results['"'.$value['rowid'].'"'][$value['servicetype_Id']]['day'] = Yii::$app->params['service'][$value['servicetype_Id']];
        }

        return $results;
    }

    public static function getAllAsArray($lang)
    {
      $sql = "SELECT * FROM tbl_price order by Id";
      $list =  static::findBySql($sql)->asArray()->all();
      $results = [];
      $array = [];
        for ($i = 0; $i < count($list); $i++) {
          if ($lang == "en" || $lang == "") {
            $results['servicename'] = Yii::$app->params['servicetype'][$list[$i]['servicetype_Id']];

          } else {
           $results['servicename'] = Yii::$app->params['servicetype_it'][$list[$i]['servicetype_Id']];
          }

          $results['Id'] = $list[$i]['Id'];
          $results['rowid'] = $list[$i]['rowid'];
          $results['mainprice'] = $list[$i]['mainprice'];
          $results['tax'] = $list[$i]['tax'];
          $results['maxguests'] = $list[$i]['maxguests'];
          $results['supplement'] = $list[$i]['supplement'];
          
          array_push($array, $results);
        }

      return $array;
    }

    public static function getAllInfoWithArray() {
        $sql = "SELECT * FROM tbl_price";
        $lists =  static::findBySql($sql)->asArray()->all();
        $results = [];
       
        foreach ($lists as $key => $value) {
            if (!isset($results[$value['rowid']])) {
              $results[$value['rowid']] = [];
            }
            $service['mainprice'] = $value['mainprice'];
            $service['servicetype'] = $value['servicetype_Id'];
            $service['tax'] = $value['tax'];
            $service['supplement'] = $value['supplement'];
            $service['maxguests'] = $value['maxguests'];
            $service['day'] = Yii::$app->params['service'][$value['servicetype_Id']];

            array_unshift($results[$value['rowid']], $service);
        }

        return $results;
    }

    public static function getRowristriction(){
        $sql = "select rowid, servicetype_Id from tbl_price order by rowid, servicetype_Id";
        $rowristriction = Yii::$app->getDb()->createCommand($sql)->queryAll();


        $result = array();
        for ($i=0; $i < count($rowristriction); $i++) { 
            $result[$rowristriction[$i]['rowid']][] =  $rowristriction[$i]['servicetype_Id'];
        }

        return $result;
    }
    
    public static function getInfo($id=""){
        $sql = "SELECT * FROM tbl_price INNER JOIN tbl_servicetype ON tbl_price.servicetype_Id = tbl_servicetype.Id";
        $lists =  static::findBySql($sql)->asArray()->all();
        if ($id != "") {
              $sql = "SELECT tbl_price.Id, rowid, tbl_servicetype.servicetype, mainprice, tax, supplement, maxguests FROM tbl_price INNER JOIN tbl_servicetype ON tbl_price.servicetype_Id = tbl_servicetype.Id where tbl_price.Id = 1";
              $lists =  static::find()->joinWith('servicetype')->where(['tbl_price.id' =>$id])->one();

        }        
        return $lists;
    }

    public static function savePrice($model) {
      $sql = sprintf("select Id, rowid, servicetype_Id from tbl_price where rowid='%s' and servicetype_Id=%d", $model['rowid'], $model['servicetype_Id']);

      $_price = Yii::$app->getDb()->createCommand($sql)->queryOne();

      if ($_price['Id'] > 0) {
          $sql = sprintf("update tbl_price set mainprice=%d, tax=%d, supplement = %d, maxguests = maxguests  where rowid='%s' and servicetype_Id=%d", $model['mainprice'], $model['tax'], $model['supplement'], $model['maxguests'], $model['rowid'], $model['servicetype_Id']);
         Yii::$app->getDb()->createCommand($sql)->execute();

         return $_price;
      } else {
          $sql = sprintf("INSERT INTO tbl_price (rowid, servicetype_Id, mainprice, tax, supplement, maxguests) VALUES ('%s', %d, %d, %d, %d, %d)", $model['rowid'], $model['servicetype_Id'], $model['mainprice'], $model['tax'], $model['supplement'], $model['maxguests']);
          $price = Yii::$app->getDb()->createCommand($sql)->execute();
        
          $sql = sprintf("select Id, rowid, servicetype_Id from tbl_price where rowid='%s' and servicetype_Id=%d", $model['rowid'], $model['servicetype_Id']);

          return Yii::$app->getDb()->createCommand($sql)->queryOne();
      }
    }
}
