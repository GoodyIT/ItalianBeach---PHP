<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tbl_guest".
 *
 * @property integer $Id
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $country
 * @property string $phonenumber
 * @property string $address
 * @property string $city
 * @property string $zipcode
 * @property integer $paymentId
 * @property string $create_time
 * @property string $state
 * @property string $recurringcount
 * @property string $totalpaid
 */
class Guest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_guest';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['firstname', 'lastname', 'email'], 'required'],
            [['paymentId', 'recurringcount', 'totalpaid'], 'integer'],
            [['firstname', 'lastname', 'email', 'city', 'zipcode', 'create_time'], 'string', 'max' => 100],
            [['country', 'address', 'state'], 'string', 'max' => 50],
            [['phonenumber'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => Yii::t('messages', 'ID'),
            'firstname' => Yii::t('messages', 'Firstname'),
            'lastname' => Yii::t('messages', 'Lastname'),
            'email' => Yii::t('messages', 'Email'),
            'country' => Yii::t('messages', 'Country'),
            'phonenumber' => Yii::t('messages', 'Phonenumber'),
            'address' => Yii::t('messages', 'Address'),
            'city' => Yii::t('messages', 'City'),
            'zipcode' => Yii::t('messages', 'Zipcode'),
            'paymentId' => Yii::t('messages', 'Payment ID'),
            'create_time' => Yii::t('messages', 'Create Time'),
            'state' => Yii::t('messages', 'State'),
            'recurringcount' => Yii::t('messages', 'Recurringcount'),
            'totalpaid' => Yii::t('messages', 'Totalpaid'),
        ];
    }

    public static function checkCustomerExistence($email) {
        $sql = sprintf('SELECT Id from tbl_guest where email =  "%s"', $email);
        $connection = Yii::$app->getDb();
        $guestId = $connection->createCommand($sql)->queryOne();  

        return  $guestId;
    }

    public static function saveOrUpdateInfo($pendingGuest, $numberOfsunshades, $totalPaid){
        date_default_timezone_set("Europe/Rome");

        $sql = sprintf('select Id, recurringcount, totalpaid from tbl_guest where email =  "%s"', $pendingGuest['email']);
        $connection = Yii::$app->getDb();
        $guest = $connection->createCommand($sql)->queryOne();

        if ($guest) {
            $recurringcount = intval($guest['recurringcount']) + $numberOfsunshades;
            $totalPaid = $totalPaid + intval($guest['totalpaid']);
            $guestId = $guest['Id'];
            $sql = sprintf('UPDATE tbl_guest set address="%s", city="%s", zipcode="%s", email="%s", phonenumber="%s", firstname="%s", lastname="%s", country="%s", create_time="%s", recurringcount=%d, totalpaid=%d where Id=%d', $pendingGuest['address'],  $pendingGuest['city'], $pendingGuest['zipcode'], $pendingGuest['email'], $pendingGuest['phonenumber'], $pendingGuest['firstname'], $pendingGuest['lastname'], $pendingGuest['country'], date('Y-m-d H:i:s'), $recurringcount, $totalPaid,  $guestId);
                Yii::$app->db->createCommand($sql)->execute();
        } else {
            $newGuest = new Guest();
            $newGuest->address = $pendingGuest['address'];
            $newGuest->city = $pendingGuest['city'];
            $newGuest->zipcode = $pendingGuest['zipcode'];
            $newGuest->email = $pendingGuest['email'];
            $newGuest->phonenumber = $pendingGuest['phonenumber'];
            $newGuest->recurringcount = $numberOfsunshades;
            $newGuest->lastname = $pendingGuest['lastname'];
            $newGuest->firstname = $pendingGuest['firstname'];
            $newGuest->country = $pendingGuest['country'];
            $newGuest->totalpaid = $totalPaid;
            $newGuest->create_time = date('Y-m-d H:i:s');
            $newGuest->save(false);
            $guestId = $newGuest->Id;
        }

        return $guestId;
    }

   /* public static function saveUpdateInfo($model) {
        $sql = sprintf("UPDATE tbl_guest set firstname='%s', lastname='%s', address='%s', city='%s', country='%s', recurringcount=%d, create_time=now(), phonenumber='%s', zipcode = '%s' where email='%s'", $model['firstname'], $model['lastname'], $model['address'], $model['city'], $model['country'], $model['recurringcount'], $model['phonenumber'], $model['zipcode'], $model['email']);

        return Yii::$app->db->createCommand($sql)->execute();
    }*/

    public static function saveWithPost($model, $state, $count=1, $totalPaid = 0) {
        date_default_timezone_set("Europe/Rome");
    
        $sql = sprintf('select Id, recurringcount, totalpaid from tbl_guest where email =  "%s"', $model["email"]);
        $connection = Yii::$app->getDb();
        $guest = $connection->createCommand($sql)->queryOne();

        if ($guest) {
            $guestId = $guest['Id'];
            $count = $count + (int)$guest['recurringcount'];
            $totalPaid = $totalPaid + (int)$guest['totalpaid'];
            $sql = sprintf('UPDATE tbl_guest set address="%s", city="%s", email="%s", phonenumber="%s", zipcode="%s", firstname="%s", lastname="%s", country="%s", create_time="%s", state="%s", recurringcount=%d, totalpaid=%d where Id=%s', $model["address"],  $model["city"], $model["email"],  $model['phonenumber'], $model["zipcode"], $model["firstname"], $model["lastname"], $model["country"], date('Y-m-d H:i:s'), $state, intval($count), $totalPaid,  $guestId);
                Yii::$app->db->createCommand($sql)->execute();
        } 
        else {
             $sql = sprintf('INSERT into tbl_guest (firstname, lastname, country, email, phonenumber, address, city, zipcode, recurringcount, create_time, state, totalpaid) 
                values ("%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", %d, "%s", "%s", %d)', $model['firstname'], $model['lastname'], $model['country'], $model["email"],  $model['phonenumber'], $model['address'], $model['city'], $model['zipcode'], $count, date('Y-m-d H:i:s'), $state, $totalPaid);
                Yii::$app->db->createCommand($sql)->execute();
                $guestId = Yii::$app->db->lastInsertID;
        }
      
        return $guestId;
    }

    public static function getAllInfo() {
        $sql = sprintf("SELECT t1.guestId, t1.Id, count(t1.seat) as numberOfBooking, concat(t2.firstname, ' ', t2.lastname) as username, t2.address, t2.city, t2.zipcode, t2.country, t2.state, t2.email, t2.phonenumber, sum(t3.paidprice) as paidprice, t3.price from tbl_bookinfo as t1 join tbl_guest as t2 on t1.guestId = t2.Id join tbl_book as t3 on t1.bookId = t3.Id where guestId != 0 and bookId!=0 group by t1.guestId order by t3.checkin");
         $connection = Yii::$app->getDb();
        $guest = $connection->createCommand($sql)->queryAll();

        return $guest;
    }   

     public static function getGuestDetail($guestId, $Id) {
        $sql = sprintf("SELECT t1.Id, t1.guestId, t1.bookingdate, t1.x, t1.y, t1.bookstate, t1.seat, concat(t2.firstname, ' ', t2.lastname) as username, t2.address, t2.city, t2.zipcode, t2.country, t2.state, t2.email, t2.phonenumber, t3.checkin, case t3.servicetype when 1 then '1 day' when 2 then '7 days' when 3 then '31 days' when 4 then 'All season' when 5 then 'Room' end as servicetype, t3.checkout, t3.paidprice, t3.price, t3.guests, t3.sunshadeseat from tbl_bookinfo as t1 join tbl_guest as t2 on t1.guestId = t2.Id join tbl_book as t3 on t1.bookId = t3.Id where t1.guestId = %d and t1.bookId !=0 and t1.Id = %d order by t3.checkin", $guestId, $Id);

        return Yii::$app->getDb()->createCommand($sql)->queryOne();
    }

    public static function getGuestInfo($id) {
        if (empty($id)) {
            return;
        }

        $sql = sprintf("select * from tbl_guest where id=%d", $id);
        return Yii::$app->getDb()->createCommand($sql)->queryOne();
    }

    public static function increaseRecurringCount($guestId, $recurringcount=1, $totalpaid=0) {
        $sql = "UPDATE tbl_guest SET recurringcount=recurringcount+$recurringcount, totalpaid=totalpaid+$totalpaid WHERE Id=$guestId";
        Yii::$app->db->createCommand($sql)->execute();
    }

    public static function decreaseInfo($guestId, $recurringcount, $totalpaid) {
        $sql = "UPDATE tbl_guest SET recurringcount=recurringcount-$recurringcount, totalpaid=totalpaid-$totalpaid WHERE Id=$guestId";
        Yii::$app->db->createCommand($sql)->execute();
    }

    public static function resetRecurringCount($selection)
    {
        $sql = sprintf("update tbl_guest set recurringcount=0 where Id in (select guest_id from tbl_book_lookup where id in (%s));", implode(',', $selection));
        return  Yii::$app->getDb()->createCommand($sql)->execute();
    }
}
