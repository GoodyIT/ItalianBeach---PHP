<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tbl_cart_milestone".
 *
 * @property integer $Id
 * @property string $date
 * @property integer $money
 * @property integer $cart_id
 * @property string $token
 * @property string $registered_at
 */
class CartMilestone extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_cart_milestone';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['money', 'cart_id'], 'integer'],
            [['date', 'token', 'registered_at'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => Yii::t('messages', 'ID'),
            'date' => Yii::t('messages', 'Date'),
            'money' => Yii::t('messages', 'Money'),
            'cart_id' => Yii::t('messages', 'Cart ID'),
            'token' => Yii::t('messages', 'Token'),
            'registered_at' => Yii::t('messages', 'Registered At'),
        ];
    }

    public static function getMilestoneWithCartId($cart_id)
    {
        $sql = "SELECT * from tbl_cart_milestone WHERE cart_id=$cart_id";
        return Yii::$app->getDb()->createCommand($sql)->queryAll();
    }

    public static function addMilestonesToCart($token, $cartId, $arrayOfDates, $arrayOfMoney)
    {
        if (count($arrayOfDates) < 1) {
            return;
        }
        date_default_timezone_set("Europe/Rome");
        $subsql = "";
        $date = date('Y-m-d H:i:s');
        for ($i=0; $i < count($arrayOfDates); $i++) { 
            $subsql .= sprintf("('%s', %d, %d, '%s', '%s'),", $arrayOfDates[$i], $arrayOfMoney[$i], $cartId, $token, $date);
        }

        $sql = "INSERT tbl_cart_milestone (date, money, cart_id, token, registered_at) VALUES " . $subsql;

        $sql = substr($sql, 0, -1);
        Yii::$app->getDb()->createCommand($sql)->execute();
    }

    public static function getTotalMoneyWithCartId($cartId)
    {
        $sql = "SELECT SUM(money) as money FROM tbl_cart_milestone WHERE cart_id=$cartId GROUP BY cart_id;";
        $paidAmount = Yii::$app->getDb()->createCommand($sql)->queryOne()['money'];
        if (empty($paidAmount)) {
            $paidAmount = 0;
        }
        return $paidAmount;
    }

    public static function getTotalMoneyFromCart($token)
    {
        $sql = "SELECT SUM(money) as money FROM tbl_cart_milestone WHERE token='$token' GROUP BY '$token';";
        $totalpaid = Yii::$app->getDb()->createCommand($sql)->queryOne()['money'];
        if (empty($totalpaid)) {
            $totalpaid = 0;
        }
        return $totalpaid;
    }

    public static function deleteMilestones($token)
    {
        $sql = "DELETE FROM tbl_cart_milestone WHERE token='$token'";
        return Yii::$app->getDb()->createCommand($sql)->execute();
    }

    public static function checkExpire()
    {
        $sql = "DELETE FROM tbl_cart_milestone WHERE registered_at < subdate(now(), interval 1 day)";
        return Yii::$app->getDb()->createCommand($sql)->execute();
    }
}
