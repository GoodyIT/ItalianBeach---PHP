<?php

namespace frontend\models;

use Yii;


/**
 * This is the model class for table "tbl_book".
 *
 * @property integer $Id
 * @property string $sunshadeseat
 * @property string $checkin
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
            [['checkin', 'comment'], 'string', 'max' => 255],
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
            'checkin' => Yii::t('messages', 'checkin'),
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
    
    public static function saveInfo($allSunshades) {
        date_default_timezone_set("Europe/Rome");
        $book = array();
        
        /*if ($checkout > $endDate) {
            $endDate =  date_create(date('Y')."-09-09");
            $checkout = $endDate;
        }   */
        
        for ($i =0; $i < count($allSunshades); $i++) {
            $Book = new Book();
            $Book->sunshadeseat = $allSunshades[$i]['sunshade'];
            $Book->checkin = date_create($allSunshades[$i]['checkin'])->format('Y-m-d');
            $Book->servicetype = $allSunshades[$i]['servicetype'];
            $Book->price = $allSunshades[$i]['price'];
            $Book->paidprice = $allSunshades[$i]['price'];
            $Book->mainprice = $allSunshades[$i]['mainprice'];
            $Book->tax = $allSunshades[$i]['tax'];
            $Book->checkout = date_create($allSunshades[$i]['checkout'])->format('Y-m-d');
            $Book->supplement = $allSunshades[$i]['supplement'];
            $Book->guests = $allSunshades[$i]['guests'];
            $Book->bookstate = "booked";
            $Book->bookedtime = date('Y-m-d H:i:s');
            $Book->save();
            array_push($book, $Book);
        }
        
        return $book;
    }

    public static function saveFromCart($sunshadeInfo, $paidAmount)
    {          
        date_default_timezone_set("Europe/Rome");
        $Book = new Book();
        // $Book->checkout =date_create($sunshadeInfo['checkout'])->format('Y-m-d');
        $Book->checkout = $sunshadeInfo['checkout'];
        $Book->sunshadeseat = $sunshadeInfo['sunshade'];
        // $Book->checkin = date_create($sunshadeInfo['checkin'])->format('Y-m-d');
        $Book->checkin = date_format(date_create(trim($sunshadeInfo['checkin'])), 'Y-m-d');
        $Book->servicetype = $sunshadeInfo['servicetype'];
        $Book->price = $sunshadeInfo['price'];
        $Book->paidprice = $paidAmount;
        $Book->mainprice = $sunshadeInfo['mainprice'];
        $Book->tax = $sunshadeInfo['tax'];
        $Book->supplement = $sunshadeInfo['supplement'];
        $Book->guests = $sunshadeInfo['guests'];
        $Book->bookstate = "booked";
        $Book->bookedtime = date('Y-m-d H:i:s');

        $Book->save();
        return $Book->Id;
    }

    public static function saveWithPost($priceLists, $array, $row, $price, $paidAmount) {

        date_default_timezone_set("Europe/Rome");
        $Book = new Book();

        $Book->checkout =date_create($array['checkout'])->format('Y-m-d');
        
        $Book->sunshadeseat = $row;
        $Book->checkin = date_create($array['checkin'])->format('Y-m-d');
        $Book->servicetype = $array['servicetype'];
        $Book->price = $price;
        $Book->paidprice = $paidAmount;
        $Book->mainprice = $priceLists[$row][0]['mainprice'];
        $Book->tax = $priceLists[$row][0]['tax'];
        $Book->supplement = $priceLists[$row][0]['supplement'];
        $Book->guests = $array['guests'];
        $Book->bookstate = "booked";
        $Book->bookedtime = date('Y-m-d H:i:s');

        $Book->save();
        return $Book->Id;    
    }

    public static function getInfoWithId($Id) {
        $sql = sprintf("SELECT Id, checkin, case servicetype when 1 then '1 day' when 2 then '7 days' when 3 then '31 days' when 4 then 'All season' when 5 then 'Room book' end as servicetype, checkout, paidprice, price, guests from tbl_book where Id=%d", $Id);
        $connection = Yii::$app->getDb();
        $book = $connection->createCommand($sql)->queryOne();

        return $book;
    }

    public static function updateInfoWithBookId($bookId, $originPaidprice, $array_price)
    {
        Yii::$app->getDb()->createCommand('UPDATE tbl_book SET paidprice= :paidprice WHERE Id = :Id', [':paidprice' => $originPaidprice + array_sum($array_price), ':Id' => $bookId])->execute();
    }

    public static function deleteBook($selection)
    {
        $sql = "DELETE FROM tbl_book where Id in ($selection);";
        Yii::$app->getDb()->createCommand($sql)->execute();
    }

}
