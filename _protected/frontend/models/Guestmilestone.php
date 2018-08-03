<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tbl_guestmilestone".
 *
 * @property string $Id
 * @property string $sunshadeseat
 * @property integer $price
 * @property string $period
 * @property integer $achieved
 * @property integer $bookId
 * @property integer $guestId
 * @property string $createddate
 * @property string $milestonegroupId
 */
class Guestmilestone extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_guestmilestone';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price', 'achieved', 'bookId', 'guestId'], 'integer'],
            [['sunshadeseat'], 'string', 'max' => 11],
            [['period', 'createddate'], 'string', 'max' => 100],
            [['milestonegroupId'], 'string', 'max' => 20],
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
            'price' => Yii::t('messages', 'Price'),
            'period' => Yii::t('messages', 'Period'),
            'achieved' => Yii::t('messages', 'Achieved'),
            'bookId' => Yii::t('messages', 'Book ID'),
            'guestId' => Yii::t('messages', 'Guest ID'),
            'createddate' => Yii::t('messages', 'Createddate'),
            'milestonegroupId' => Yii::t('messages', 'Milestonegroup ID'),
        ];
    }

    public static function saveFromCart($bookId, $guestId, $sunshade, $milestones, $timestamp)
    {
        if (count($milestones) < 1) {
            return;
        }
        date_default_timezone_set("Europe/Rome");
        $subsql = "";
        for ($i=0; $i < count($milestones); $i++) { 
            $subsql .= sprintf("(%d, '%s', %d, %d, '%s', 1, '%s', '%s'),", $milestones[$i]['money'], date_format(date_create($milestones[$i]['date']), 'Y-m-d'), $bookId, $guestId, $sunshade, date('Y-m-d H:i:s'), $timestamp);
        }

        $sql = "INSERT tbl_guestmilestone (price, period, bookId, guestId, sunshadeseat, achieved, createddate, milestonegroupId) VALUES " . $subsql;

        $sql = substr($sql, 0, -1);
        Yii::$app->getDb()->createCommand($sql)->execute();
    }

    public static function saveWithGuestId($array_price, $array_date, $bookId, $guestId, $sunshadeseat, $timestamp, $acheieved = 1) 
    {
        if (count($array_price) == 0) {
            return;
        }
        date_default_timezone_set("Europe/Rome");
        $subsql = "";
        for ($i = 0; $i < count($array_price); $i++) {
            if ($array_price[$i] == 0) {
                return;
            }

            $subsql .= sprintf("(%d, '%s', %d, %d, '%s', 1, '%s', '%s'),", $array_price[$i], date_format(date_create($array_date[$i]), 'Y-m-d'), $bookId, $guestId, $sunshadeseat, date('Y-m-d H:i:s'), $timestamp);
        }

        $sql = "INSERT tbl_guestmilestone (price, period, bookId, guestId, sunshadeseat, achieved, createddate, milestonegroupId) VALUES " . $subsql;

        $sql = substr($sql, 0, -1);
        Yii::$app->getDb()->createCommand($sql)->execute();
    }

    public static function getMilestonesWithGuestId($bookId, $Id, $sunshadeseat) {
        $sql = sprintf("SELECT t1.price, t1.milestonegroupId as timestamp, t1.period from tbl_guestmilestone as t1 join tbl_book_lookup as t2 where t1.sunshadeseat = t2.sunshade and t1.milestonegroupId = t2.milestonegroup_id and t1.bookId = %d and t1.guestId = %d and  t1.sunshadeseat='%s';", $bookId, $Id, $sunshadeseat);
        return Yii::$app->getDb()->createCommand($sql)->queryAll();
    }

    public static function getMilestonesWithLookupId($lookupId)
    {
        $sql = sprintf("SELECT t1.price, t1.milestonegroupId as timestamp, t1.period from tbl_guestmilestone as t1 join tbl_book_lookup as t2 where t1.sunshadeseat = t2.sunshade and t1.milestonegroupId = t2.milestonegroup_id and t2.id = %d;", $lookupId);

        return Yii::$app->getDb()->createCommand($sql)->queryAll();
    } 
}
