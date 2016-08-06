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
 *
 * @property Guesthistory[] $guesthistories
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
            [['address', 'city','firstname', 'lastname', 'email', 'zipcode', 'phonenumber'], 'required'],
            [['paymentId', 'recurringcount'], 'integer'],
            [['firstname', 'lastname', 'email', 'city', 'zipcode', 'create_time'], 'string', 'max' => 100],
            [['country', 'address', 'state'], 'string', 'max' => 50],
            [['phonenumber'], 'string', 'max' => 20],
            ['email', 'email']
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
            'recurringcount' => Yii::t('messages', 'Recurring Count'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGuesthistories()
    {
        return $this->hasMany(Guesthistory::className(), ['guestId' => 'Id']);
    }

    public static function addBookInfo($model, $paymentId){
        date_default_timezone_set("UTC");
        $guest = new Guest();
        $guest->address = $model->address;
        $guest->city = $model->city;
        $guest->zipcode = $model->zipcode;
        $guest->firstname = $model->firstname;
        $guest->lastname = $model->lastname;
        $guest->email = $model->email;
        $guest->country = $model->country;
        $guest->phonenumber = $model->phonenumber;
        $guest->paymentId = $model->paymentId;
        $guest->state = $model->state;
        $guest->create_time = date('Y-m-d H:i:s');
        $guest->save();

        return $guest->Id;
    }

    public static function checkCustomerExistence($email) {
        $sql = sprintf('select Id from tbl_guest where email =  "%s"', $email);
        $connection = Yii::$app->getDb();
        $guestId = $connection->createCommand($sql)->queryOne();  

        return  $guestId;
    }

    public function saveInfoIntoCookie() {
        $session = Yii::$app->session;

        if (!$session->isActive) $session->open();

        $session['guest'] = [
            'address' => $this->address,
            'city' => $this->city,
            'zipcode' => $this->zipcode,
            'email' => $this->email,
            'phonenumber' => $this->phonenumber,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'country' => $this->country,
        ];

        $session->close();
    }

    public static function saveOrUpdateInfo(){
        date_default_timezone_set("UTC");

        $session = Yii::$app->session;

        if (!$session->isActive) $session->open();

        $guest = $session['guest'];

        $session->close();

        $sql = sprintf('select Id, recurringcount from tbl_guest where email =  "%s"', $guest['email']);
        $connection = Yii::$app->getDb();
        $guestId = $connection->createCommand($sql)->queryOne();

        if ($guestId) {
            $recurringcount = $guestId['recurringcount'];
            $guestId = $guestId['Id'];
            $sql = sprintf('update tbl_guest set address="%s", city="%s", zipcode="%s", email="%s", phonenumber="%s", firstname="%s", lastname="%s", country="%s", create_time="%s", recurringcount=%d where Id=%d', $guest['address'],  $guest['city'], $guest['zipcode'], $guest['email'], $guest['phonenumber'], $guest['firstname'], $guest['lastname'], $guest['country'], date('d/m/Y H:i:s'), intval($recurringcount)+1,  $guestId);
                Yii::$app->db->createCommand($sql)->execute();
        } else {
            $newGuest = new Guest();
            $newGuest->address = $guest['address'];
            $newGuest->city = $guest['city'];
            $newGuest->zipcode = $guest['zipcode'];
            $newGuest->email = $guest['email'];
            $newGuest->phonenumber = $guest['phonenumber'];
            $newGuest->recurringcount = 0;
            $newGuest->lastname = $guest['lastname'];
            $newGuest->firstname = $guest['firstname'];
            $newGuest->country = $guest['country'];
            $newGuest->save(false);
            $guestId = $newGuest->Id;
        }

        return $guestId;
    }

  /*  public static function savePaymentInfoIntoCookie($paymentId, $bookState) {
        $session = Yii::$app->session;

        if (!$session->isActive) $session->open();

        $session['guest'] = [
            'paymentId' => $paymentId,
            'bookState' => $bookState,
        ];

        $session->close();
    }*/

    public static function updateBookInfo($guestId, $paymentId, $bookState) {
        date_default_timezone_set("UTC");

        $sql = sprintf("update tbl_guest set paymentId='%s', state='%s', create_time='%s' where Id=%d", $paymentId, $bookState, date('Y-m-d H:i:s'), $guestId);
        Yii::$app->db->createCommand($sql)->execute();
    }

    public static function updateBookState($bookId, $state) {
        date_default_timezone_set("UTC");
         $sql = sprintf("update tbl_guest set paymentId='%s', state='%s', create_time='%s' where bookId=%d", $paymentId, $state, date('Y-m-d H:i:s'), $bookId);
        Yii::$app->db->createCommand($sql)->execute();
    }

    public static function saveUpdateInfo($model) {
        $sql = sprintf("update tbl_guest set firstname='%s', lastname='%s', address='%s', city='%s', country='%s', recurringcount=%d, create_time=now(), phonenumber='%s', zipcode = '%s' where email='%s'", $model['firstname'], $model['lastname'], $model['address'], $model['city'], $model['country'], $model['recurringcount'], $model['phonenumber'], $model['zipcode'], $model['email']);

        return Yii::$app->db->createCommand($sql)->execute();
    }

    public static function saveWithPost($model, $state) {
        date_default_timezone_set("UTC");
        $guest = new Guest();
    
        $sql = sprintf('select Id, recurringcount from tbl_guest where email =  "%s"', $model["email"]);
        $connection = Yii::$app->getDb();
        $guestId = $connection->createCommand($sql)->queryAll();

        if ($guestId) {
            $recurringcount = $guestId[0]['recurringcount'];
            $guestId = $guestId[0]['Id'];
            $sql = sprintf('update tbl_guest set address="%s", city="%s", email="%s", phonenumber="%s", zipcode="%s", firstname="%s", lastname="%s", country="%s", create_time="%s", state="%s", recurringcount=%d where Id=%s', $model["address"],  $model["city"], $model["email"],  $model['phonenumber'], $model["zipcode"], $model["firstname"], $model["lastname"], $model["country"], date('d/m/Y H:i:s'), $state, intval($recurringcount)+1,  $guestId);
                Yii::$app->db->createCommand($sql)->execute();
        } 
        else {
             $sql = sprintf('insert into tbl_guest (firstname, lastname, country, email, phonenumber, address, city, zipcode, create_time, state) values ("%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s")', $model['firstname'], $model['lastname'], $model['country'], $model["email"],  $model['phonenumber'], $model['address'], $model['city'], $model['zipcode'], date('d/m/Y H:i:s'), $state);
                Yii::$app->db->createCommand($sql)->execute();
                $guestId = Yii::$app->db->lastInsertID;
        }
      
        return $guestId;
    }

    public static function getAllInfo() {
        $sql = sprintf("select t1.guestId, t1.Id, count(t1.seat) as numberOfBooking, concat(t2.firstname, ' ', t2.lastname) as username, t2.address, t2.city, t2.zipcode, t2.country, t2.state, t2.email, t2.phonenumber, sum(t3.paidprice) as paidprice, t3.price from tbl_bookinfo as t1 join tbl_guest as t2 on t1.guestId = t2.Id join tbl_book as t3 on t1.bookId = t3.Id where guestId != 0 and bookId!=0 group by t1.guestId order by t3.arrival" );
         $connection = Yii::$app->getDb();
        $guest = $connection->createCommand($sql)->queryAll();

        return $guest;
    }   

     public static function getGuestDetail($guestId, $Id) {
        $sql = sprintf("select t1.Id, t1.guestId, t1.bookingdate, t1.x, t1.y, t1.bookstate, t1.seat, concat(t2.firstname, ' ', t2.lastname) as username, t2.address, t2.city, t2.zipcode, t2.country, t2.state, t2.email, t2.phonenumber, t3.arrival, case t3.servicetype when 1 then '1 day' when 2 then '7 days' when 3 then '31 days' when 4 then 'All season' when 5 then 'Room' end as servicetype, if(substr(t1.seat, 1, 1) = 1, t3.checkout, adddate(t3.arrival, interval case servicetype when 1 then 1 when 2 then 7 when 3 then 31 when 4 then 100 else 1 end day)) as checkout, t3.paidprice, t3.price, t3.guests, t3.sunshadeseat from tbl_bookinfo as t1 join tbl_guest as t2 on t1.guestId = t2.Id join tbl_book as t3 on t1.bookId = t3.Id where t1.guestId = %d and t1.bookId !=0 and t1.Id = %d order by t3.arrival", $guestId, $Id);

        return Yii::$app->getDb()->createCommand($sql)->queryOne();
    }

    // public static function getAllInfo() {
    //     $sql = sprintf('select Id, guestId, bookId from tbl_bookinfo where guestId != 0 and bookId!=0');
    //     $connection = Yii::$app->getDb();
    //     $bookinfo = $connection->createCommand($sql)->queryAll();

    //     $result = array();
    //     for ($i = 0; $i < count($bookinfo); $i++) {
    //         $guestId = $bookinfo[$i]['guestId'];
    //         $sql = sprintf('select * from tbl_guest where Id=%s', $guestId);

    //         $guest = $connection->createCommand($sql)->queryAll()[0];
    //         if ($guest) {
    //             $result[$guestId]['username'] = $guest['firstname'] . ' ' . $guest['lastname'];
    //             $result[$guestId]['address'] = $guest['address'];
    //             $result[$guestId]['email'] = $guest['email'];
    //             $result[$guestId]['phonenumber'] = $guest['phonenumber'];

    //         }
          
    //         $bookId = $bookinfo[$i]['bookId'];
    //         $sql = sprintf('select * from tbl_book where Id=%s', $bookId);
    //         $book = $connection->createCommand($sql)->queryAll()[0];
    //         if ($book) {
    //             $result[$guestId]['arrival'] = $book['arrival'];
    //             $result[$guestId]['bookedtime'] = $book['bookedtime'];
    //             $result[$guestId]['sunshadeseat'] = $book['sunshadeseat'];
    //             $result[$guestId]['servicetype'] = Yii::$app->params['servicetype'][$book['servicetype']];
    //             if (!isset($result[$guestId]['price'])) {
    //                 $result[$guestId]['price'] = 0;
    //             }  
                
    //             $result[$guestId]['price'] += intval($book['price']);
    //         }
    //     }

    //     return $result;
    // }
}
