<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tbl_cart".
 *
 * @property integer $Id
 * @property string $token
 * @property integer $sunshade_id
 * @property string $sunshade
 * @property string $checkin
 * @property integer $servicetype
 * @property string $checkout
 * @property integer $guests
 * @property integer $price
 * @property integer $mainprice
 * @property integer $tax
 * @property integer $supplement
 * @property integer $maxguests
 * @property string $previous
 * @property integer $valid
 * @property string $registered_at
 */
class Cart extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_cart';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sunshade_id', 'servicetype', 'guests', 'price', 'mainprice', 'tax', 'supplement', 'maxguests', 'valid'], 'integer'],
            [['token', 'checkin', 'checkout', 'registered_at'], 'string', 'max' => 255],
            [['sunshade'], 'string', 'max' => 10],
            [['previous'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => Yii::t('messages', 'ID'),
            'token' => Yii::t('messages', 'Token'),
            'sunshade_id' => Yii::t('messages', 'Sunshade ID'),
            'sunshade' => Yii::t('messages', 'Sunshade'),
            'checkin' => Yii::t('messages', 'Checkin'),
            'servicetype' => Yii::t('messages', 'Servicetype'),
            'checkout' => Yii::t('messages', 'Checkout'),
            'guests' => Yii::t('messages', 'Guests'),
            'price' => Yii::t('messages', 'Price'),
            'mainprice' => Yii::t('messages', 'Mainprice'),
            'tax' => Yii::t('messages', 'Tax'),
            'supplement' => Yii::t('messages', 'Supplement'),
            'maxguests' => Yii::t('messages', 'Maxguests'),
            'previous' => Yii::t('messages', 'Previous'),
            'valid' => Yii::t('messages', 'Valid'),
            'registered_at' => Yii::t('messages', 'Registered At'),
        ];
    }

    public static function saveToCart($token, $sunshade_id, $sunshade, $previous="")
    {
        $date = date('Y-m-d H:i:s');
        $sql= "INSERT tbl_cart (token, sunshade_id, sunshade, previous, registered_at) VALUES ('$token', $sunshade_id, '$sunshade', '$previous', '$date')";

        Yii::$app->getDb()->createCommand($sql)->execute();
        return Yii::$app->getDb()->getLastInsertID();
    }

    public static function removeSunshadeFromCart($token, $sunshade_id, $checkin, $servicetype)
    {
        $sql = "DELETE FROM tbl_cart WHERE sunshade_id=$sunshade_id and $checkin=$checkin and servicetype=$servicetype";

        Yii::$app->getDb()->createCommand($sql)->execute();
    }

    public static function removeSunshadeFromCartWithId($id)
    {
        $sql = "DELETE FROM tbl_cart WHERE id=$id";

        Yii::$app->getDb()->createCommand($sql)->execute();
    }

    public static function updateCart($data)
    {
        $intervals = Yii::$app->params['intervals'];
        $checkout=date_create($data["checkin"]);
        if ($data["checkin"] != "" && $data["servicetype"] != -1) {
            date_add($checkout, date_interval_create_from_date_string($intervals[$data["servicetype"]]));
        }

        $sql = sprintf("UPDATE tbl_cart SET checkin='%s', checkout='%s', servicetype=%d, guests=%d, price=%d, mainprice=%d, tax=%d, supplement=%d, maxguests=%d, valid=%d WHERE Id=%d", $data["checkin"], $checkout->format("Y-m-d"), $data["servicetype"], $data["guests"], $data["price"], $data["mainprice"], $data["tax"], $data["supplement"], $data["maxguests"], $data["valid"], $data["cartid"]);
       return Yii::$app->getDb()->createCommand($sql)->execute();
    }

    public static function getAllCarts(){
       $sql = "SELECT * from tbl_cart where valid = 1";

       return Yii::$app->getDb()->createCommand($sql)->queryAll();
    }

    public static function getAllCartsWithToken($token){
       $sql = "SELECT * from tbl_cart WHERE token='$token'";

       return Yii::$app->getDb()->createCommand($sql)->queryAll();
    }

    public static function getAllValidSunshades($token)
    {
        $sql = "SELECT * from tbl_cart WHERE valid =1 and token='$token'";

       return Yii::$app->getDb()->createCommand($sql)->queryAll();
    }

    public static function getTotalPriceOfCart($token){
        $sql = "SELECT sum(price) as totalPrice from tbl_cart WHERE valid =1 and token='$token'";

        return Yii::$app->getDb()->createCommand($sql)->queryOne()['totalPrice'];
    }

    public static function clearCart($token)
    {
        $sql = "DELETE FROM tbl_cart WHERE token='$token'";
        return Yii::$app->getDb()->createCommand($sql)->execute();
    }

    public static function checkExpire()
    {
        $sql = "DELETE FROM tbl_cart WHERE registered_at < subdate(now(), interval 1 day)";
        return Yii::$app->getDb()->createCommand($sql)->execute();
    }
}
