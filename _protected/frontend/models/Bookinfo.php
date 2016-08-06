<?php

namespace frontend\models;

use Faker\Provider\DateTime;
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
 * @property string $timestamp
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
            [['bookstate'], 'string', 'max' => 11],
            [['seat'], 'string', 'max' => 40],
            [['bookingdate'], 'string', 'max' => 100],
            [['bookingdate'], 'BookingdateValidation']
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
            'bookingdate' => Yii::t('messages', 'Bookedtime'),
            'milestonegroupId' => Yii::t('messages', 'Milestone Group Id'),
        ];
    }

    public static function getAvailableSunshadePair() {
        $sql = "select Id, bookstate from tbl_bookinfo";
        $sunshadeList = Yii::$app->getDb()->createCommand($sql)->queryAll();

        $result = array();
        for ($i=0; $i < count($sunshadeList); $i++) { 
            $result[$sunshadeList[$i]['Id']] =  $sunshadeList[$i]['bookstate'];
        }

        return $result;
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

    public static function getAllInfo($day="") {
        // date_default_timezone_set("UTC");

        // $sql = "select a.Id, a.x, a.y, a.seat, b.arrival, b.servicetype, a.bookstate from tbl_bookinfo as a left join tbl_book as b on a.bookId = b.Id group by a.seat order by a.Id";
        
        // $connection = Yii::$app->getDb();
        // $bookinfo = $connection->createCommand($sql)->queryAll();

        // if ($day == "") {
        //     $day = date('Y-m-d');
        // }

        // $service = ["0" => 1, "1"=>7, "2"=>31, "3"=>100];
        // for($i = 0; $i < count($bookinfo); $i++){
        //     for($j = 0; $j < count($bookinfo[$i]); $j++){
        //         $index = ($bookinfo[$i]['servicetype'])-1;
        //        if(!empty($bookinfo[$i]['arrival']) && (strtotime($bookinfo[$i]['arrival']) + $service[$index]*24*3600 ) < strtotime($day))
        //          {
        //              unset($bookinfo[$i]['bookstate']);
        //              $bookinfo[$i]['bookstate'] = "available";
        //              break;
        //          }
        //     }
        // }

        // for($i = 0; $i < count($bookinfo); $i++){
        //     for($j= 0; $j < count($bookinfo[$i]); $j++){
        //         unset($bookinfo[$i]['arrival']);
        //         unset($bookinfo[$i]['servicetype']);
        //     }
        // }

        $sql = "select Id, seat, x, y, bookstate from tbl_bookinfo";
        $arrayOfOrigin = Yii::$app->getDb()->createCommand($sql)->queryAll();

        if (empty($day)) {
            $day = date('Y-m-d');
        }

         $sql = sprintf("select a.Id, a.x, a.y, a.seat, if(a.bookstate='booked' and adddate(if(b.arrival != '', b.arrival, '3220-07-01'), interval case b.servicetype when 1 then 1 when 2 then 7 when 3 then 31 when 4 then 100 else 1 end day) > '%s', 'booked', 'available') as bookstate from tbl_bookinfo as a left join tbl_book as b on a.bookId = b.Id where a.bookId > 0 and a.guestId > 0 group by a.seat order by a.seat", date_format(date_create($day), 'Y-m-d'));

        $connection = Yii::$app->getDb();
        $bookinfo = $connection->createCommand($sql)->queryAll();

        $result = array();
        for ($i = 0; $i < count($arrayOfOrigin); $i++ ){
            if (isset($bookinfo[$i]['Id'])) {
               $result[$i] = $bookinfo[$i];          
            } else {
                $result[$i] = $arrayOfOrigin[$i];
            }         
        }

        return  json_encode($arrayOfOrigin);
    }

    public static function getAllInfoForFrontend($day="") {
         $sql = "select Id, seat, x, y, bookstate from tbl_bookinfo where Id < 176";
        $arrayOfOrigin = Yii::$app->getDb()->createCommand($sql)->queryAll();

        if (empty($day)) {
            $day = date('Y-m-d');
        }

         $sql = sprintf("select a.Id, a.x, a.y, a.seat, if(a.bookstate='booked' and adddate(if(b.arrival != '', b.arrival, '3220-07-01'), interval case b.servicetype when 1 then 1 when 2 then 7 when 3 then 31 when 4 then 100 else 1 end day) > '%s', 'booked', 'available') as bookstate from tbl_bookinfo as a left join tbl_book as b on a.bookId = b.Id where a.bookId > 0 and a.guestId > 0 and a.Id < 176 group by a.seat order by a.seat", date_format(date_create($day), 'Y-m-d'));

        $connection = Yii::$app->getDb();
        $bookinfo = $connection->createCommand($sql)->queryAll();

        $result = array();
        for ($i = 0; $i < count($arrayOfOrigin); $i++ ){
            if (isset($bookinfo[$i]['Id'])) {
               $result[$i] = $bookinfo[$i];          
            } else {
                $result[$i] = $arrayOfOrigin[$i];
            }         
        }

        return  json_encode($arrayOfOrigin);
    }
    
    public static function getInfoForBackend() {
        $sql = "select a.Id, a.bookstate, a.seat, a.bookingdate, b.Id as BookDetail, c.Id as GuestDetail".
                " from tbl_bookinfo as a left join tbl_book as b on a.bookId = b.Id". 
                " left join tbl_guest as c on a.guestId = c.Id group by a.seat order by a.seat";
        
        $connection = Yii::$app->getDb();
        $bookinfo = $connection->createCommand($sql)->queryAll();

        return $bookinfo;
    }



    public static function updateSeatState($seat, $state="booking"){
        date_default_timezone_set("UTC");
        $sql = sprintf("update tbl_bookinfo set bookstate = '%s', bookingdate = '%s' where seat = '%s'", $state, date('Y-m-d H:i:s'), $seat);
       // $sql = "select * from tbl_bookinfo where seat = '". $seat . "'";

        return  Yii::$app->getDb()->createCommand($sql)->execute();
    }
    
    public static function updateInfo($seat, $guestId,  $bookId, $state = "booked", $timestamp){
        date_default_timezone_set("UTC");
        $sql = sprintf("update tbl_bookinfo set bookId=%d, guestId=%d, bookstate='%s', bookingdate='%s', milestonegroupId=%d where seat='%s'", $bookId, $guestId, $state, date('Y-m-d H:i:s'), $timestamp , $seat);
        return  Yii::$app->getDb()->createCommand($sql)->execute();
    }

    public static function updateTimestamp($seat, $timestamp) {
        $sql = sprintf("update tbl_bookinfo set milestonegroupId=%d where seat='%s'", $timestamp , $seat);
        return  Yii::$app->getDb()->createCommand($sql)->execute();
    }
    
    public static function resetBookInfo(){
        $sql = "update tbl_bookinfo set bookstate='available' where bookstate='booking';";
        return  Yii::$app->getDb()->createCommand($sql)->execute();
    }

    public static function resetBookInfoWithGuestId($guestId){
        $sql = sprintf("update tbl_bookinfo set bookstate='available', bookId = 0, guestId = 0, milestonegroupId = '', bookingdate = '' where guestId = %d;", $guestId);
        return  Yii::$app->getDb()->createCommand($sql)->execute();
    }    

    public static function resetBookInfoWithArray($selection){
        $sql = sprintf("update tbl_bookinfo set bookstate='available', bookId = 0, guestId = 0, milestonegroupId = '', bookingdate = '' where Id in ('%s');", implode(', ', $selection));
        return  Yii::$app->getDb()->createCommand($sql)->execute();
    } 

     public static function resetBookInfoWithId($id){
        $sql = sprintf("update tbl_bookinfo set bookstate='available', bookId = 0, guestId = 0, milestonegroupId = '', bookingdate = '' where Id = %d", $id);
        return  Yii::$app->getDb()->createCommand($sql)->execute();
    }     

    public static function checkAvailability($seat, $date){
        date_default_timezone_set("UTC");

        $sql = "select b.arrival, b.servicetype, a.bookstate from tbl_bookinfo as a left join tbl_book as b on a.bookId = b.Id WHERE a.seat = '" . $seat . "' group by a.seat order by a.Id";

        $connection = Yii::$app->getDb();
        $bookinfo = $connection->createCommand($sql)->queryAll();
        if (empty($bookinfo['arrival']))
            return true;
        $service = ["0" => 1, "1"=>7, "2"=>31, "3"=>100];
        $index = ($bookinfo['servicetype'])-1;
        return $index;
        if((strtotime($bookinfo['arrival']) + $service[$index]*24*3600 ) < strtotime($date)){
            return true;
        } else {
            return false;
        }
    }

    // Dashboard

    public static function getTotalBookedSunshade(&$total) {
        $sql = "select count(*) as cnt, substr(seat, 1, 1) as sunshadeseat from tbl_bookinfo where bookstate='booked' and guestId != 0 and bookId != 0 group by substr(seat, 1, 1);";
        $connection = Yii::$app->getDb();
        $history = $connection->createCommand($sql)->queryAll();

        $total = 0;
        $result = array();
        for ($i = 0; $i < count($history); $i++) {
            $total += $history[$i]['cnt'];
            $result[$history[$i]['sunshadeseat']] = $history[$i]['cnt'];
        }

        $seats = ['A', 'B', 'C', 'D', 'E'];
        foreach ($seats as $key => $value) {
            if (!isset($result[$value])) {
                $result[$value] = 0;
            }
        }

        return $result;
     }

     public static function getAlternativeDailyCustomers() {
        date_default_timezone_set("UTC");

        $sql = "select sum(t2.guests) as customers, substr(t1.bookingdate, 1, 10) as bookingdate from tbl_bookinfo as t1 join tbl_book as t2 on t1.bookId = t2.Id where t2.bookstate='booked' and t1.bookingdate != '' group by substr(t2.arrival, 1, 10);";
        $connection = Yii::$app->getDb();
        $history = $connection->createCommand($sql)->queryAll();

        $bookedArray = array();
        foreach ($history as $value) {
            $bookedArray[$value['bookingdate']] = $value['customers'];
        }

        $startDate = \DateTime::createFromFormat('Y-m-d', date('Y')."-06-01");
        $endDate = \DateTime::createFromFormat('Y-m-d', date('Y')."-09-09");
        $dateRange = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate);

        $temp = array();
        foreach ($dateRange as $date) {
            $dateValue = strtotime($date->format('Y-m-d'));
            if (!isset($bookedArray[$date->format('Y-m-d')])) {
                $temp[] = "[$dateValue, 0]";
            } 
            else {
                $val = $bookedArray[$date->format('Y-m-d')];
                $temp[] = "[$dateValue, $val]";
            }
        }
        $results['data'] = [implode(", ", $temp)];
        $results['label'] = 'Number of Customers';

        return json_encode($results);
     }

     public static function getDailyCustomers() {
        date_default_timezone_set("UTC");
        $sql = "select sum(t2.guests) as customers, substr(t2.arrival, 1, 10) as arrival from tbl_bookinfo as t1 join tbl_book as t2 on t1.bookId = t2.Id where t2.bookstate='booked' and t1.bookingdate != '' group by substr(t2.arrival, 1, 10);";
        $connection = Yii::$app->getDb();
        $history = $connection->createCommand($sql)->queryAll();

        $bookedArray = array();
        foreach ($history as $value) {
            $bookedArray[$value['arrival']] = $value['customers'];
        }

        $startDate = \DateTime::createFromFormat('Y-m-d', date('Y')."-06-01");
        $endDate = \DateTime::createFromFormat('Y-m-d', date('Y')."-09-09");
        $dateRange = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate);

        $results = array();
        foreach ($dateRange as $date) {
            
            if (!isset($bookedArray[$date->format('Y-m-d')])) {
                $dateValue = $date->modify( '-1 month' )->format('Y,m,d');
                $results[] = "[Date.UTC($dateValue), 0]";
            } 
            else {
                $val = $bookedArray[$date->format('Y-m-d')];
                $dateValue = $date->modify( '-1 month' )->format('Y,m,d');
                $results[] = "[Date.UTC($dateValue), $val]";
            }
        }
        return join($results, ',');
     }

     public static function getTotoalRevenueBySunshade(&$total) {
        $sql = "SELECT SUM(t2.paidprice) AS price, substr(t2.sunshadeseat, 1, 1) as sunshade  FROM tbl_bookinfo AS t1 JOIN tbl_book AS t2 ON t1.bookId = t2.Id GROUP BY substr(t2.sunshadeseat, 1, 1);";
        $connection = Yii::$app->getDb();
        $histories = $connection->createCommand($sql)->queryAll();

        $revenue = array();
        $sunshadeKey = ['A', 'B', 'C', 'D', 'E'];
        $arrayColor = ['A'=>"#F44336", 'B'=>"#03A9F4", 'C' => "#8BC34A", 'D'=>"#FFEB3B", 'E' => "#009688"];
        $arrValues = array_values($histories);

        $temp = array();
        for ($i = 0; $i < count($arrValues); $i++) {
              $temp[$arrValues[$i]['sunshade']] = $arrValues[$i]['price'];
        }

        foreach ($sunshadeKey as $value) {
            $revenue[$value]['data'] = isset($temp[$value]) ? $temp[$value] : 0;
            $revenue[$value]['color'] = $arrayColor[$value];
            $revenue[$value]['label'] = "$value (". utf8_encode("&euro;"). $revenue[$value]['data'] . ")";
            $total += $revenue[$value]['data'];
        }

        $result = array();
        foreach ($revenue as $key => $value) {
            array_push($result, $value);
        }

        return json_encode($result);
    }

    public static function getTotoalRevenueBySunshadeByPercentage($from="", $to=""){
        $sql = "select * from tbl_bookhistory order by sunshadeseat";
        $connection = Yii::$app->getDb();
        $history = $connection->createCommand($sql)->queryAll();

        $revenue = array();
        $revenue['total'] = 0;
        $revenue['A']['origin'] = 0;
        $revenue['B']['origin'] = 0;
        $revenue['C']['origin'] = 0;
        $revenue['D']['origin'] = 0;
        $revenue['E']['origin'] = 0;

        for ($i = 0; $i < count($history); $i++) { 
            $revenue['total'] += $history[$i]['price'];
            $revenue[$history[$i]['sunshadeseat'][0]]['origin'] +=  intval($history[$i]['price']); 
        }

        $revenue['A']['percentage'] = (intval($revenue['A']['origin']) / intval($revenue['total'])) * 100.0;
        $revenue['B']['percentage'] = (intval($revenue['B']['origin']) / intval($revenue['total'])) * 100.0;
        $revenue['C']['percentage'] = (intval($revenue['C']['origin']) / intval($revenue['total'])) * 100.0;
        $revenue['D']['percentage'] = (intval($revenue['D']['origin']) / intval($revenue['total'])) * 100.0;
        $revenue['E']['percentage'] = (intval($revenue['E']['origin']) / intval($revenue['total'])) * 100.0;

        return $revenue;
    }

     public static function getTotalCustomersBySunshade(&$total) {
        $sql = "SELECT SUM(t2.guests) AS guests, substr(t2.sunshadeseat, 1, 1) as sunshade  FROM tbl_bookinfo AS t1 JOIN tbl_book AS t2 ON t1.bookId = t2.Id GROUP BY substr(t2.sunshadeseat, 1, 1);";
        $connection = Yii::$app->getDb();
        $histories = $connection->createCommand($sql)->queryAll();


        $customers = array();
        $sunshadeKey = ['A', 'B', 'C', 'D', 'E'];
        $arrayColor = ['A'=>"#F44336", 'B'=>"#03A9F4", 'C' => "#8BC34A", 'D'=>"#FFEB3B", 'E' => "#009688"];
        $arrValues = array_values($histories);

        $temp = array();
        for ($i = 0; $i < count($arrValues); $i++) {
              $temp[$arrValues[$i]['sunshade']] = $arrValues[$i]['guests'];
        }

        foreach ($sunshadeKey as $value) {
            $customers[$value]['data'] = isset($temp[$value]) ? $temp[$value] : 0;
            $customers[$value]['color'] = $arrayColor[$value];
            $_data = $customers[$value]['data'];
            $customers[$value]['label'] = "$value ($_data)";
            $total += $customers[$value]['data'];
        }

        $result = array();
        foreach ($customers as $key => $value) {
            array_push($result, $value);
        }

        return json_encode($result);
     }

    public static function findBestCustomers() {
        $sql = "select sum(t2.paidprice) as totalprice, count(t1.guestId) as recurringcount, concat(t3.firstname, ' ', t3.lastname) as guestname, t1.guestId from tbl_bookinfo as t1 join tbl_book as t2 on t1.bookId = t2.Id join tbl_guest as t3 on t1.guestId = t3.Id where t1.bookstate = 'booked' group by t3.Id   order by totalprice desc limit 7;";
        $connection = Yii::$app->getDb();
        $bestCustomers = $connection->createCommand($sql)->queryAll();

        return $bestCustomers;
    }

     public static function getAllBookDetails($guestId) {
        $sql = sprintf("select t1.Id, t1.guestId, t1.x, t1.y, t1.bookstate, adddate(t3.arrival, interval case t3.servicetype when 1 then 1 when 2 then 7 when 3 then 31 when 4 then 100 else 1 end day) as checkout, t1.bookingdate, concat(t2.firstname, ' ', t2.lastname) as username, t2.country,  t2.address, t2.email, t2.phonenumber, t3.arrival, t3.guests, t1.seat as sunshade, t1.seat, case t3.servicetype when 1 then '1 day' when 2 then '7 days' when 3 then '31 days' when 4 then 'All season' when 5 then 'Room' end as servicetype, t3.price, t3.paidprice, if(substr(t1.seat, 1, 1) = 1, t3.checkout, adddate(t3.arrival, interval case servicetype when 1 then 1 when 2 then 7 when 3 then 31 when 4 then 100 else 1 end day)) as checkout  from tbl_bookinfo as t1 join tbl_guest as t2 on t1.guestId = t2.Id join tbl_book as t3 on t1.bookId = t3.Id where guestId=%d", $guestId );

        $connection = Yii::$app->getDb();
        $guest = $connection->createCommand($sql)->queryAll();

        return $guest;
     }

     public static function getCurrentBookinfo($sunshadeseat) {
        $sql = sprintf("select t1.Id, t1.seat, t2.arrival, case t2.servicetype when 1 then '1 day' when 2 then '7 days' when 3 then '31 days' when 4 then 'All season' end as servicetype, if(substr(t1.seat, 1, 1) = 1, t2.checkout, adddate(t2.arrival, interval case servicetype when 1 then 1 when 2 then 7 when 3 then 31 when 4 then 100 else 1 end day)) as checkout, t2.paidprice, t2.price, t2.guests, concat(t3.firstname, ' ', t3.lastname) as username, t3.email, t3.address, t3.phonenumber from tbl_bookinfo as t1 join tbl_book as t2 on t1.bookId = t2.Id join tbl_guest as t3 on t1.guestId = t3.Id where t1.seat='%s'", $sunshadeseat);

        $connection = Yii::$app->getDb();
        $guest = $connection->createCommand($sql)->queryOne();

        return $guest;
     }

    public static function getAllAvailableSunshade($day=0) {
       
        $sql = sprintf("select a.Id, a.seat, b.arrival, b.servicetype, if(a.bookstate='booked', 'available', 'booked') as bookstate, concat(c.firstname, ' ', c.lastname) as username from tbl_bookinfo as a left join tbl_book as b on a.bookId = b.Id join tbl_guest as c on a.guestId = c.Id where a.bookstate='booked' and adddate(if(b.arrival != '', b.arrival, '2220-07-01'), interval case b.servicetype when 1 then 1 when 2 then 7 when 3 then 31 when 4 then 100 else 1 end day) < adddate(now(), interval %d day) group by a.seat order by a.Id", $day);
        
        $connection = Yii::$app->getDb();
        $bookinfo = $connection->createCommand($sql)->queryAll();

        foreach ($bookinfo as $key => $value) {
            if ($day == 0) {
                $title = $value['seat'] . " will be available today.";
                $title_it = $value['seat'] . " sarà oggi disponibili.";
                Notification::saveNewNotification($title, $title_it, $value['seat']);
            } else {
                $title = $value['seat'] . " ". sprintf(" will be available %d day later.", $day);
                $title_it = $value['seat'] . " ". sprintf(" sarà disponibile tra %d giorni.", $day);
                Notification::saveNewNotification($title, $title_it, $value['seat']);
            }
        }

        return  $bookinfo;
    }

    public static function checkExpiredate() {
        $sql = "update tbl_bookinfo as a set a.bookstate='available', a.guestId = '', a.bookingdate = '', a.bookId = '', a.milestonegroupId = '' where a.bookId in (select b.Id from tbl_book as b where a.bookstate='booked' and adddate(if(b.arrival != '', b.arrival, '2220-07-01'), interval case b.servicetype when 1 then 1 when 2 then 7 when 3 then 31 when 4 then 100 else 1 end day) < now());";

        $connection = Yii::$app->getDb();
        $bookinfo = $connection->createCommand($sql)->execute();
    }

    public static function getAllBookInfo() {
        $sql = "select t1.Id, t1.guestId, concat(t2.firstname, ' ', t2.lastname) as username, t2.address, t2.city, t2.zipcode, t2.country, t2.state, t2.email, t2.phonenumber, t3.arrival, if(substr(t1.seat, 1, 1) = 1, t3.checkout, adddate(t3.arrival, interval case servicetype when 1 then 1 when 2 then 7 when 3 then 31 when 4 then 100 else 1 end day)) as checkout, t3.paidprice, t3.price, t3.sunshadeseat from tbl_bookinfo as t1 join tbl_guest as t2 on t1.guestId = t2.Id join tbl_book as t3 on t1.bookId = t3.Id where guestId != 0 and bookId!=0 order by t3.arrival";

        return Yii::$app->getDb()->createCommand($sql)->queryAll();
    }

    public static function getGuestId($id) {
        $sql = sprintf("select guestId from tbl_bookinfo where Id = %d;", $id);

        return Yii::$app->getDb()->createCommand($sql)->queryOne();
    }

    public static function getSunshadeseatWithId($id) {
         $sql = sprintf("select seat from tbl_bookinfo where Id = %d;", $id);

        return Yii::$app->getDb()->createCommand($sql)->queryOne();
    }
}
