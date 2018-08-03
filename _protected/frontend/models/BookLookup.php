<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tbl_book_lookup".
 *
 * @property integer $id
 * @property string $sunshade
 * @property integer $bookinfo_id
 * @property integer $book_id
 * @property integer $guest_id
 * @property integer $milestonegroup_id
 * @property string $bookstate
 * @property string $booktoken
 * @property integer $deleted
 * @property string $booked_at
 */
class BookLookup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_book_lookup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sunshade', 'bookinfo_id', 'book_id', 'guest_id', 'milestonegroup_id', 'bookstate', 'booked_at'], 'required'],
            [['bookinfo_id', 'book_id', 'guest_id', 'milestonegroup_id', 'deleted'], 'integer'],
            [['sunshade', 'bookstate'], 'string', 'max' => 20],
            [['booktoken', 'booked_at'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('messages', 'ID'),
            'sunshade' => Yii::t('messages', 'Sunshade'),
            'bookinfo_id' => Yii::t('messages', 'Bookinfo ID'),
            'book_id' => Yii::t('messages', 'Book ID'),
            'guest_id' => Yii::t('messages', 'Guest ID'),
            'milestonegroup_id' => Yii::t('messages', 'Milestonegroup ID'),
            'bookstate' => Yii::t('messages', 'Bookstate'),
            'booktoken' => Yii::t('messages', 'Booktoken'),
            'deleted' => Yii::t('messages', 'Deleted'),
            'booked_at' => Yii::t('messages', 'Booked At'),
        ];
    }

    /*
     *  Get the all sunshades with booking info
    */
    public static function getAllSunshades()
    {
        $sql = "SELECT c.Id, c.seat, c.x, c.y, c.bookstate from tbl_bookinfo as c where c.booktype = 'sunshade'";
        
        return Yii::$app->getDb()->createCommand($sql)->queryAll();
    }

    public static function getDateRange($from, $to)
    {
        $array = [];
        $startDate = \DateTime::createFromFormat('Y-m-d', date_format(date_create($from), 'Y-m-d'));
        $endDate = \DateTime::createFromFormat('Y-m-d', date_format(date_create($to), 'Y-m-d'));
        
        $dateRange = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate);
        foreach ($dateRange as $date) {
            $array[] = $date->format('Y-m-d');
        }
        if ($from == $to) {
            $array[] = $startDate->format('Y-m-d');
        }
        return $array;
    }

    /*
     *  Get the all sunshades with booking info within cetain range
    */
    public static function getAllSunshadesWithinRange($from="", $to="", $token, $booktype='sunshade')
    {
        $sql = "select c.Id, c.seat, c.x, c.y, c.bookstate from tbl_bookinfo as c where c.booktype = '$booktype'";
        $arrayOfOrigin = Yii::$app->getDb()->createCommand($sql)->queryAll();

        $from = date_format(date_create(trim($from)), 'Y-m-d');
        $to = date_format(date_create(trim($to)), 'Y-m-d');

        $sql = sprintf("SELECT c.Id, c.seat, b.checkin, b.checkout, if(('%s' < SUBDATE(b.checkin, 1) AND '%s' > SUBDATE(b.checkin, 1)) OR  ('%s' < ADDDATE(b.checkout, 1) AND ADDDATE(b.checkout, 1) < '%s'), 'possible', a.bookstate) as bookstate   from tbl_book_lookup as a left join  tbl_bookinfo as c on a.bookinfo_id = c.Id left join tbl_book as b on a.book_id = b.Id  where c.booktype = '$booktype' and a.deleted = 0  order by c.seat", $from, $to, $from, $to);

        $bookinfo = Yii::$app->getDb()->createCommand($sql)->queryAll();
        $cart = Cart::getAllCarts();
        $result = [];
        for ($i = 0; $i < count($arrayOfOrigin); $i++ ){
            $result[$i] = $arrayOfOrigin[$i];
            $result[$i]['bookedinfo'] = [];
            $result[$i]['cartid'] = "";
            $result[$i]['previous'] = "";
            for ($j=0; $j < count($bookinfo); $j++) { 
                if ($arrayOfOrigin[$i]['Id'] == $bookinfo[$j]['Id']) {
                    array_push($result[$i]['bookedinfo'], $bookinfo[$j]);
                    if ($bookinfo[$j]['bookstate'] == "possible") {
                        /* check it has pending books 
                           if $from < $bookinfo[$j]['checkin'] OR
                            $to > $bookinfo[$j]['checkout']
                            anyone can find the possiblity to book that sunshade for the rest of the days.

                            1. Subtract booked dates from "from~to" array - $subArray

                            2. if sunshade in the cart is booked for some portion of the dates between 'from' and 'to'
                            subtract that portion from the $subArray

                            3. if $subArray is empty then, no possibility
                             else possibility
                        */
                        $bigArray = static::getDateRange($from, $to);
                        $smallArray = static::getDateRange(max($from, $bookinfo[$j]['checkin']), min($to, $bookinfo[$j]['checkout']));
                        $subArray = array_diff($bigArray, $smallArray);
                        for($k = 0; $k < count($cart); $k++)
                        {
                            if ($cart[$k]['sunshade_id'] == $arrayOfOrigin[$i]['Id'])
                            {
                                if ($cart[$k]['checkin'] != "" && $cart[$k]['checkout'] != "") {
                                    if ($cart[$k]['checkin'] > $bookinfo[$j]['checkout']  && $cart[$k]['checkin'] < $to) {
                                        $_to = date_create($to);
                                        $_to = $_to->modify('-1 day')->format('Y-m-d');
                                        $smallArray = static::getDateRange($cart[$k]['checkin'], min($_to, $cart[$k]['checkout']));
                                        $subArray = array_diff($subArray, $smallArray);
                                    } 

                                    if($cart[$k]['checkout'] < $bookinfo[$j]['checkin'] && $cart[$k]['checkout'] > $from) {
                                        $_from = date_create($from);
                                        $_from = $_from->modify('-1 day')->format('Y-m-d');
                                        $smallArray = static::getDateRange(max($_from, $cart[$k]['checkin']),  $cart[$k]['checkout']);
                                        $subArray = array_diff($subArray, $smallArray);
                                    }
                                }
                            }
                        }

                        /* 3. if $subArray is empty then, no possibility
                             else possibility */
                        if (!empty($subArray) && $result[$i]['bookstate'] != 'bookingcart') {
                            $result[$i]['bookstate'] = 'possible';
                        }
                    }
                }
            }

            for($k = 0; $k < count($cart); $k++)
            {
                if ($cart[$k]['sunshade_id'] == $arrayOfOrigin[$i]['Id']) {
                    if ($cart[$k]['checkin'] != "" && $cart[$k]['checkout'] != "") {
                        array_push($result[$i]['bookedinfo'], $cart[$k]);
                    }
                    if (trim($token) == trim($cart[$k]['token']))
                    {
                        $result[$i]['bookstate'] = 'bookingcart';
                        $result[$i]['cartid'] = $cart[$k]['Id'];
                        $result[$i]['previous'] = $cart[$k]['previous'];
                    }
                    /*if ($result[$i]['bookstate'] != 'bookingcart' && $result[$i]['bookstate'] != 'possible') {
                       $result[$i]['bookstate'] = 'booked';
                    }*/
                }
            }
        }

        return  $result;
    }

    public static function saveBookInfo($sunshade, $guest_id,  $book_id, $timestamp)
    {
        $sql = sprintf("Select Id from tbl_bookinfo where seat='%s'", trim($sunshade));
        $bookinfo_id = Yii::$app->getDb()->createCommand($sql)->queryOne()['Id'];

        date_default_timezone_set("Europe/Rome");

        $date = date('Y-m-d H:i:s');

        $sql = "INSERT tbl_book_lookup (sunshade, bookinfo_id, book_id, guest_id, milestonegroup_id, bookstate, booked_at) VALUES ('$sunshade', $bookinfo_id, $book_id, $guest_id, '$timestamp', 'booked', '$date')";
        Yii::$app->getDb()->createCommand($sql)->execute();

        return Yii::$app->getDb()->getLastInsertID();
    }

    public static function getAllBookDetailsForGuest($guestId) {
        $sql = sprintf("SELECT t1.id as booklookupId, t1.bookstate, t1.booked_at, t1.book_id as bookId, t4.Id, t4.x, t4.y, t4.seat as sunshade, t4.seat,  t2.checkin, case t2.servicetype when 1 then '1 day' when 2 then '7 days' when 3 then '31 days' when 4 then 'All season' when 4 then 'Room Booking' end as servicetype, t2.checkout, t2.paidprice, t2.price, t2.guests, concat(t3.firstname, ' ', t3.lastname) as username, t3.email, t3.country, t3.address, t3.phonenumber from tbl_book_lookup as t1 join tbl_bookinfo as t4 on t1.bookinfo_id = t4.Id join tbl_book as t2 on t1.book_id = t2.Id join tbl_guest as t3 on t1.guest_id = t3.Id where t1.guest_id=%d and t1.deleted = 0 order by t1.booked_at DESC", $guestId );

        return Yii::$app->getDb()->createCommand($sql)->queryAll();
    }

    public static function getAllBookDetailsByRooms($roomId)
    {
        $sql = sprintf("SELECT t1.id as booklookupId, t1.bookstate, t1.booked_at, t1.book_id as bookId, t4.Id, t4.x, t4.y, t4.seat as sunshade, t4.seat,  t2.checkin, case t2.servicetype when 1 then '1 day' when 2 then '7 days' when 3 then '31 days' when 4 then 'All season' when 4 then 'Room Booking' end as servicetype, t2.checkout, t2.paidprice, t2.price, t2.guests, concat(t3.firstname, ' ', t3.lastname) as username, t3.email, t3.country, t3.address, t3.phonenumber from tbl_book_lookup as t1 join tbl_bookinfo as t4 on t1.bookinfo_id = t4.Id join tbl_book as t2 on t1.book_id = t2.Id join tbl_guest as t3 on t1.guest_id = t3.Id where t1.bookinfo_id=%d and t1.deleted = 0 order by t1.booked_at DESC", $roomId );

        return Yii::$app->getDb()->createCommand($sql)->queryAll();
    }

    public static function getAllBookDetailsByCustomers()
    {
        $sql = "SELECT sum(t2.paidprice) as totalpaid, count(t1.guest_id) as recurringcount, concat(t3.firstname, ' ', t3.lastname) as username, t1.guest_id as guestId, t3.email, t3.country, t3.address, t3.phonenumber, t3.state from tbl_book_lookup as t1 join tbl_book as t2 on t1.book_id = t2.Id join tbl_guest as t3 on t1.guest_id = t3.Id where t1.deleted = 0 group by t3.Id order by recurringcount";

        return Yii::$app->getDb()->createCommand($sql)->queryAll();
    }

    public static function getAllBookDetails()
    {
        $sql = sprintf("SELECT t1.Id, t1.bookstate, t1.booked_at, t1.book_id as bookId, t1.guest_id as guestId, t4.Id as sunshadeId, t4.x, t4.y, t4.seat as sunshade, t4.seat, t2.Id as bookId,  t2.checkin, case t2.servicetype when 1 then '1 day' when 2 then '7 days' when 3 then '31 days' when 4 then 'All season' when 5 then 'Room book' end as servicetype, t2.checkout, t2.paidprice, t2.price, t2.guests, concat(t3.firstname, ' ', t3.lastname) as username, t3.Id as guestId, t3.email, t3.country, t3.address, t3.state, t3.recurringcount, t3.phonenumber from tbl_book_lookup as t1 join tbl_bookinfo as t4 on t1.bookinfo_id = t4.Id join tbl_book as t2 on t1.book_id = t2.Id inner join tbl_guest as t3 on t1.guest_id = t3.Id where t1.deleted = 0 order by t1.booked_at DESC");

        $connection = Yii::$app->getDb();
        $guest = $connection->createCommand($sql)->queryAll();

        return $guest;
    }

    public static function getCustomerDetailInfo($id)
    {
        $sql = sprintf("SELECT t1.id, t1.sunshade, case t2.servicetype when 1 then '1 day' when 2 then '7 days' when 3 then '31 days' when 4 then 'All season' when 5 then 'Room Booking' end as servicetype, t2.checkin, t2.checkout, t2.price, t2.paidprice, t2.guests, concat(t3.firstname, ' ', t3.lastname) as username, t3.email, t3.country, t3.address, t3.phonenumber  from tbl_book_lookup as t1 join tbl_book as t2 on t1.book_id = t2.Id join tbl_guest as t3 on t1.guest_id = t3.Id where t1.id=%d and t1.deleted = 0 order by t1.booked_at DESC", $id);

        return Yii::$app->getDb()->createCommand($sql)->queryOne();
    }

    public static function updateTimestamp($sunshade, $bookId, $guestId, $timestamp) {
        $sql = sprintf("UPDATE tbl_book_lookup set milestonegroup_id=%d where sunshade='%s' and book_id=%d and guest_id=%d and deleted = 0", $timestamp , $sunshade, $bookId, $guestId);
        return  Yii::$app->getDb()->createCommand($sql)->execute();
    }

    public static function deleteLookup($selection)
    {
        $sql = "DELETE FROM tbl_book_lookup where Id in ($selection);";
        Yii::$app->getDb()->createCommand($sql)->execute();
    }

    // Dashboard
    public static function getTotalBookedSunshade(&$total, $from, $to) {
        $sql = "SELECT count(t1.id) as cnt, substr(t1.sunshade, 1, 1) as sunshade from tbl_book_lookup as t1 join tbl_book as t2 on t1.book_id = t2.Id where t1.bookinfo_id < 169 and t1.deleted = 0 and '$from' <= t2.checkin OR '$to' >= t2.checkout group by substr(sunshade, 1, 1);";
        $connection = Yii::$app->getDb();
        $history = $connection->createCommand($sql)->queryAll();

        $total = 0;
        $result = array();
        for ($i = 0; $i < count($history); $i++) {
            $total += $history[$i]['cnt'];
            $result[$history[$i]['sunshade']] = $history[$i]['cnt'];
        }

        $seats = ['A', 'B', 'C', 'D', 'E'];
        foreach ($seats as $key => $value) {
            if (!isset($result[$value])) {
                $result[$value] = 0;
            }
        }

        return $result;
     }

      public static function getDailyCustomers($from, $to) {
        date_default_timezone_set("Europe/Rome");
        $sql = "SELECT sum(t2.guests) as customers, checkin from tbl_book_lookup as t1 join tbl_book as t2 on t1.book_id = t2.Id where t1.bookinfo_id < 169 and t1.deleted = 0 and '$from' <= t2.checkin OR '$to' >= t2.checkout group by t2.checkin order by checkin";
        $connection = Yii::$app->getDb();
        $history = $connection->createCommand($sql)->queryAll();

        $bookedArray = array();
        foreach ($history as $value) {
            $bookedArray[$value['checkin']] = $value['customers'];
        }

        $startDate = \DateTime::createFromFormat('Y-m-d', date_format(date_create($from), 'Y-m-d'));
        $endDate = \DateTime::createFromFormat('Y-m-d', date_format(date_create($to), 'Y-m-d'));
        
        $dateRange = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate);

        $results = array();
        foreach ($dateRange as $date) {
            $val = 0;
            if (isset($bookedArray[$date->format('Y-m-d')])) {
                $val = $bookedArray[$date->format('Y-m-d')];
            }
            // $dateValue = strtotime($date->format('Y-m-d'));
            // $results[] = "[$dateValue, $val]";
          $dateValue = $date->modify( '-1 month' )->format('Y,m,d');
          $results[] = "[Date.UTC($dateValue), $val]";
        }
        return join($results, ',');
     }

    public static function getTotoalRevenueBySunshade(&$total, $from, $to) {
        $sql = "SELECT SUM(t2.paidprice) AS price, substr(t2.sunshadeseat, 1, 1) as sunshade  FROM tbl_book_lookup AS t1 JOIN tbl_book AS t2 ON t1.book_id = t2.Id where t1.bookinfo_id < 169 and t1.deleted = 0 and '$from' <= t2.checkin OR '$to' >= t2.checkout GROUP BY substr(t2.sunshadeseat, 1, 1);";
        $histories = Yii::$app->getDb()->createCommand($sql)->queryAll();

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

    public static function getTotalCustomersBySunshade(&$total, $from, $to) {
        $sql = "SELECT SUM(t2.guests) AS guests, substr(t2.sunshadeseat, 1, 1) as sunshade  FROM tbl_book_lookup AS t1 JOIN tbl_book AS t2 ON t1.book_id = t2.Id where t1.bookinfo_id < 169 and t1.deleted = 0 and '$from' <= t2.checkin OR '$to' >= t2.checkout GROUP BY substr(t2.sunshadeseat, 1, 1);";
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

     public static function findBestCustomers($from, $to) {
        $sql = "SELECT sum(t2.paidprice) as totalprice, count(t1.guest_id) as recurringcount, concat(t3.firstname, ' ', t3.lastname) as guestname, t1.guest_id as guestId from tbl_book_lookup as t1 join tbl_book as t2 on t1.book_id = t2.Id join tbl_guest as t3 on t1.guest_id = t3.Id where t1.bookinfo_id < 169 and t1.deleted = 0 group by t3.Id order by totalprice desc limit 7;";
        $connection = Yii::$app->getDb();
        $bestCustomers = $connection->createCommand($sql)->queryAll();

        return $bestCustomers;
    }

    public static function getAllAvailableSunshade($day=0) {
       $sql = sprintf("SELECT t1.id, t1.bookinfo_id, t1.sunshade, if(t1.bookstate='booked', 'available', 'available') as bookstate, t2.checkin, t2.checkout, t2.servicetype, concat(t3.firstname, ' ', t3.lastname) as username from tbl_book_lookup as t1 join tbl_book as t2 on t1.book_id = t2.Id join tbl_guest as t3 on t1.guest_id = t3.Id where t1.deleted = 0 and t2.checkout <= adddate(now(), interval %d day) order by t1.sunshade", $day);

        $connection = Yii::$app->getDb();
        $bookinfo = $connection->createCommand($sql)->queryAll();

        foreach ($bookinfo as $key => $value) {
            if ($day == 0) {
                $title = $value['sunshade'] . " will be available today.";
                $title_it = $value['sunshade'] . " sarà oggi disponibili.";
            } else {
                $title = $value['sunshade'] . " ". sprintf(" will be available %d day later.", $day);
                $title_it = $value['sunshade'] . " ". sprintf(" sarà disponibile tra %d giorni.", $day);
            }
            Notification::saveNewNotification($title, $title_it, $value['username'], $value['bookinfo_id'], $value['sunshade']);

        }

        if ($day == 0 && count($bookinfo) != 0) {
            $Ids = [];
            for ($i=0; $i < count($bookinfo); $i++) { 
                $ids[] = $bookinfo[$i]['id'];
            }
            $ids = implode(',', $ids);
            Yii::$app->getDb()->createCommand("UPDATE tbl_book_lookup SET deleted= 0 WHERE Id IN ($ids)")->execute();

            $sql = "UPDATE tbl_bookinfo as a set a.bookstate='available' where a.Id not in (SELECT t1.bookinfo_id from tbl_book_lookup as t1 where t1.deleted = 0);";

            Yii::$app->getDb()->createCommand($sql)->execute();
        }

        return  $bookinfo;
    }

    public static function checkExpiredate() {
        $sql = "UPDATE tbl_book_lookup as t1 join tbl_book as t2 on t1.book_id = t2.Id set t1.deleted=1 where t1.deleted = 0 and t2.checkout < now();";
        Yii::$app->getDb()->createCommand($sql)->execute();

        $sql = "UPDATE tbl_bookinfo as a set a.bookstate='available' where a.Id not in (SELECT t1.bookinfo_id from tbl_book_lookup as t1   where t1.deleted = 0);";

        Yii::$app->getDb()->createCommand($sql)->execute();
    }

    public static function getId($bookinfo_id, $book_id, $guest_id)
    {
        $sql = sprintf("SELECT id from tbl_book_lookup where $bookinfo_id = %d and $book_id=%d and guest_id=%d", $bookinfo_id, $book_id, $guest_id);
        return Yii::$app->getDb()->createCommand($sql)->queryOne()['id'];
    }

    public function getMilestonegroupId($bookinfo_id, $book_id, $guest_id)
    {
        $sql = sprintf("SELECT milestonegroup_id from tbl_book_lookup where $bookinfo_id = %d and $book_id=%d and guest_id=%d", $bookinfo_id, $book_id, $guest_id);
        return Yii::$app->getDb()->createCommand($sql)->queryOne()['milestonegroup_id'];
    }
}
