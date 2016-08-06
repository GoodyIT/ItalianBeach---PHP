<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tbl_book".
 *
 * @property integer $Id
 * @property string $sunshadeseat
 * @property string $arrival
 * @property integer $servicetype
 * @property integer $price
 * @property integer $paidprice
 * @property integer $mainprice
 * @property integer $tax
 * @property integer $supplement
 * @property integer $guests
 * @property string $bookstate
 * @property string $comment
 * @property string $bookedtime
 */
class Book extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_book';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['servicetype', 'price', 'paidprice', 'mainprice', 'tax', 'supplement', 'guests'], 'integer'],
            [['sunshadeseat', 'bookedtime'], 'string', 'max' => 100],
            [['arrival', 'comment'], 'string', 'max' => 255],
            [['bookstate'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => Yii::t('messages', 'ID'),
            'sunshadeseat' => Yii::t('messages', 'Sunshadeseat'),
            'arrival' => Yii::t('messages', 'Arrival'),
            'servicetype' => Yii::t('messages', 'Servicetype'),
            'price' => Yii::t('messages', 'Price'),
            'paidprice' => Yii::t('messages', 'Paidamount'),
            'mainprice' => Yii::t('messages', 'Mainprice'),
            'tax' => Yii::t('messages', 'Tax'),
            'supplement' => Yii::t('messages', 'Supplement'),
            'guests' => Yii::t('messages', 'Guests'),
            'bookstate' => Yii::t('messages', 'Bookstate'),
            'comment' => Yii::t('messages', 'Comment'),
            'bookedtime' => Yii::t('messages', 'Bookedtime'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicetype()
    {
        return $this->hasOne(Servicetype::className(), ['Id' => 'servicetype']);
    }

    public static function getInfoOne($id) {
        return Book::find()->joinWith('servicetype')->where(['tbl_book.id' => $id])->one();
    }

    public static function getInfo(){
        $sql = "SELECT * FROM tbl_book INNER JOIN tbl_servicetype ON tbl_book.servicetype = tbl_servicetype.Id";
        $lists =  static::findBySql($sql)->asArray()->all();
        return $lists;
    }
    
    public static function saveInfo() {
        date_default_timezone_set("UTC");
        $cookies = Yii::$app->request->cookies;
        $sunshadeseat  ="";
        $arrival = "";
        $price = "";
        $mainprice = "";
        $tax = "";
        $supplement = "";
        $servicetype = "";
        $guests = "";

        if (($cookie = $cookies->get('sunshadeseat')) !== null) 
            $sunshadeseat = $cookie->value;

        if (($cookie = $cookies->get('arrival')) !== null) 
                $arrival = $cookie->value;

        if (($cookie = $cookies->get('price')) !== null) 
                $price = $cookie->value;  

        if (($cookie = $cookies->get('mainprice')) !== null) 
                $mainprice = $cookie->value;  

        if (($cookie = $cookies->get('tax')) !== null) 
                $tax = $cookie->value;  

        if (($cookie = $cookies->get('supplement')) !== null) 
                $supplement = $cookie->value;  

        if (($cookie = $cookies->get('servicetype')) !== null) 
                $servicetype = $cookie->value;
        if (($cookie = $cookies->get('guests')) !== null) 
                $guests = $cookie->value;
        
        $sql = "select * from tbl_book where sunshadeseat = '" . $sunshadeseat . "'";
        $Book = Book::findBySql($sql)->one();
        if ($Book == null) {
            $Book = new Book();
            $Book->sunshadeseat = $sunshadeseat;
            $Book->arrival = $arrival;
        }

        $Book->arrival = $arrival;
        $Book->servicetype = $servicetype;
        $Book->price = $price;
        $Book->paidprice = $price;
        $Book->mainprice = $mainprice;
        $Book->tax = $tax;
        $Book->supplement = $supplement;
        $Book->guests = $guests;
        $Book->bookstate = "booked";
        $Book->bookedtime = date('Y-m-d H:i:s');

        $Book->save();
        return $Book;
    }

    public static function saveWithPost($model, $paidAmount) {
        date_default_timezone_set("UTC");
        $Book = new Book();

        $Book->sunshadeseat = $model['sunshadeseat'];
        $Book->arrival = $model['arrival'];
        if ($model['sunshadeseat'][0] == 1) {
            $Book->checkout = $model['checkout'];
        }
        $Book->servicetype = $model['servicetype'];
        $Book->price = $model['price'];
        $Book->paidprice = $paidAmount;
        $Book->mainprice = $model['mainprice'];
        $Book->tax = $model['tax'];
        $Book->supplement = $model['supplement'];
        $Book->guests = $model['guests'];
        $Book->bookstate = "booked";
        $Book->bookedtime = date('Y-m-d H:i:s');

        $Book->save();
        return $Book->Id;    
    }

    public static function getInfoWithId($Id) {
        $sql = sprintf("select Id, arrival, servicetype, if(substr(sunshadeseat, 1, 1) = 1, checkout, adddate(arrival, interval case servicetype when 1 then 1 when 2 then 7 when 3 then 31 when 4 then 100 else 1 end day)) as checkout, paidprice, price, guests from tbl_book where Id=%d", $Id);
         $connection = Yii::$app->getDb();
        $book = $connection->createCommand($sql)->queryOne();

        return $book;
    }
}
