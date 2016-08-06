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
            [['price', 'achieved', 'guestId'], 'integer'],
            [['sunshadeseat'], 'string', 'max' => 11],
            [['period', 'createddate'], 'string', 'max' => 20],
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
            'guestId' => Yii::t('messages', 'Guest ID'),
            'createddate' => Yii::t('messages', 'Createddate'),
            'milestonegroupId' => Yii::t('messages', 'Milestonegroup ID'),
        ];
    }

    public static function saveWithGuestId($array_price, $array_date, $guestId, $sunshadeseat, $timestamp) 
    {
        date_default_timezone_set("UTC");

        $result = array();
        for ($i = 0; $i < count($array_price); $i++) {
            if ($array_price[$i] == 0) {
                return;
            }
            
            $milestone = new Guestmilestone();
            $milestone->price = $array_price[$i];
            $milestone->period = date_format(date_create($array_date[$i]), 'Y-m-d');
            $milestone->guestId = $guestId;
            $milestone->sunshadeseat = $sunshadeseat;
            $milestone->achieved = 0;
            $milestone->createddate = date('Y-m-d H:i:s');
            $milestone->milestonegroupId = $timestamp;
            $milestone->save(false);
            array_push($result, $milestone);
        }
        return $result;
    }

    public static function getMilestonesWithGuestId($Id, $sunshadeseat) {
        $sql = sprintf("select t1.price, t1.milestonegroupId as timestamp, t1.period from tbl_guestmilestone as t1 join tbl_bookinfo as t2 on t1.sunshadeseat = t2.seat and t1.milestonegroupId = t2.milestonegroupId where  t1.guestId = %d and  t1.sunshadeseat='%s';", $Id, $sunshadeseat);
        $connection = Yii::$app->getDb();
        $milestones = $connection->createCommand($sql)->queryAll();

        return $milestones;
    }
}
