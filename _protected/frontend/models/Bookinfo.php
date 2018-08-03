<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tbl_bookinfo".
 *
 * @property integer $Id
 * @property double $x
 * @property double $y
 * @property string $bookstate
 * @property string $seat
 * @property integer $bookId
 * @property integer $guestId
 * @property string $bookingdate
 * @property string $booktoken
 * @property string $milestonegroupId
 * @property string $booktype
 */
class Bookinfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_bookinfo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['x', 'y'], 'number'],
            [['bookId', 'guestId'], 'integer'],
            [['bookstate', 'seat', 'bookingdate', 'booktoken', 'milestonegroupId', 'booktype'], 'string', 'max' => 255],
            // [['bookingdate'], 'BookingdateValidation']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => Yii::t('messages', 'ID'),
            'x' => Yii::t('messages', 'X'),
            'y' => Yii::t('messages', 'Y'),
            'bookstate' => Yii::t('messages', 'Bookstate'),
            'seat' => Yii::t('messages', 'Seat'),
            'bookId' => Yii::t('messages', 'Book ID'),
            'guestId' => Yii::t('messages', 'Guest ID'),
            'booktoken' => Yii::t('messages', 'Book Token'),
            'bookingdate' => Yii::t('messages', 'Bookedtime'),
            'milestonegroupId' => Yii::t('messages', 'Milestone Group Id'),
        ];
    }

     public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['sunshade'] = ['bookingdate'];//Scenario Values Only Accepted
        return $scenarios;
    }

    /***
     *  Sunshade booking date should be ranged from June 1 to Sep 8
     *
     * @param $attribute
     */
    public function BookingdateValidation($attribute){
        $time=strtotime($this->$attribute);
        $day=date("d",$time);
        $month=date("F",$time);
        $year=date("Y",$time);

        $currentYear = date("Y");
        $minMonth = 6;
        $minday = 1;
        $maxMonth = 9;
        $maxDay = 8;

        if ($year <= $currentYear || $day <=$minday || $day >= $maxDay || $month <= $minMonth || $month >= $maxMonth)
            $this->addError($attribute, Yii::t('messages', 'Sunshade season Only from June 1 to Sep 8'));
    }

    public static function getSunshadeList() {
        $sql = "select Id, seat from tbl_bookinfo";

        $sunshadeList = Yii::$app->getDb()->createCommand($sql)->queryAll();

        $result = array();
        for ($i=0; $i < count($sunshadeList); $i++) { 
            $result[$sunshadeList[$i]['Id']] =  $sunshadeList[$i]['seat'];
        }

        return $result;
    }

    public static function updateInfo($seat){
        $sql = sprintf("update tbl_bookinfo set  bookstate='booked', booktoken = '' where seat='%s'", trim($seat));
        return Yii::$app->getDb()->createCommand($sql)->execute();
    }

    public static function resetBookInfo(){
        $sql = "update tbl_bookinfo set bookstate='available' where bookstate='booking' and booktoken = ''";
        return  Yii::$app->getDb()->createCommand($sql)->execute();
    }

    public static function resetBookInfoWithGuestId($guestId){
        $sql = sprintf("update tbl_bookinfo set bookstate='available', bookId = 0, guestId = 0, milestonegroupId = '', bookingdate = '' where guestId = %d;", $guestId);
        return  Yii::$app->getDb()->createCommand($sql)->execute();
    }    

    public static function updateBookInfoWithArray($selection){
        $sql = "update tbl_bookinfo set bookstate='available' where Id in (select bookinfo_id from tbl_book_lookup where id in ($selection));";
        return  Yii::$app->getDb()->createCommand($sql)->execute();
    } 

     public static function resetBookInfoWithId($id){
        $sql = sprintf("update tbl_bookinfo set bookstate='available', bookId = 0, guestId = 0, milestonegroupId = '', bookingdate = '' where Id = %d", $id);
        return  Yii::$app->getDb()->createCommand($sql)->execute();
    }     


   

    public static function getGuestId($id) {
        if (empty($id)) {
            return;
        }
        $sql = sprintf("select guestId from tbl_bookinfo where Id = %d;", $id);

        return Yii::$app->getDb()->createCommand($sql)->queryOne();
    }

    public static function getSunshadeseatWithId($id) {
         $sql = sprintf("select seat from tbl_bookinfo where Id = %d;", $id);

        return Yii::$app->getDb()->createCommand($sql)->queryOne();
    }

    public static function getSunshadeRows($selection){
        $sql = sprintf("select substr(seat, 1, 1) as row from tbl_bookinfo where Id in (%s) group by row", $selection);   

        return Yii::$app->getDb()->createCommand($sql)->queryAll();
    }

    public static function getRooms($selection)
    {
        $sql = sprintf("select seat as row from tbl_bookinfo where Id in (%s) group by seat", $selection);
        return Yii::$app->getDb()->createCommand($sql)->queryAll();
    }

    public static function checkinArray($val, &$array){
        if (!in_array($val, $array)) {
            array_push($array, $val);
        }   
    }

    public static function getSunshadeRowsBySunshades($sunshades){
        $rows = array();
        foreach ($sunshades as $key => $value) {
           Bookinfo::checkinArray($value, $rows);
        }
        sort($rows);
        return $rows;
    }

    public static function getAllSunshades($selection){
        $sql = sprintf("select seat from tbl_bookinfo where Id in (%s) ", $selection);

        return Yii::$app->getDb()->createCommand($sql)->queryAll();
    }

    public static function setToken($sunshade)
    {
        $bookinfo = Bookinfo::findOne(['seat' => $sunshade]);
        $bookinfo->booktoken = Yii::$app->security->generateRandomString() . '_' . time();
        $bookinfo->bookstate = "booking";

        $bookinfo->save();
    }

    public static function removeToken($sunshade)
    {
        $bookinfo = Bookinfo::findOne(['seat' => $sunshade]);
        $bookinfo->booktoken = null;

        $bookinfo->save();
    }

    public static function checkToken($sunshade)
    {
        $bookinfo = Bookinfo::findOne(['seat' => $sunshade]);
        if ($bookinfo->booktoken == null || $bookinfo->booktoken == '')
        {
            return "-1";
        }

        return $bookinfo->booktoken; 
    }

    public static function updateState($id, $state)
    {
        $sql = "UPDATE tbl_bookinfo SET bookstate='pending' where id=$id";
        Yii::$app->getDb()->createCommand($sql)->execute();
    }
}
