<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tbl_notificationreminder".
 *
 * @property integer $Id
 * @property string $reminderdate
 * @property string $adminIP
 */
class Notificationreminder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_notificationreminder';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reminderdate', 'adminIP'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => Yii::t('messages', 'ID'),
            'reminderdate' => Yii::t('messages', 'Reminderdate'),
            'adminIP' => Yii::t('messages', 'Admin Ip'),
        ];
    }

    public static function updateAdminReminder() {
        $sql = sprintf("update tbl_notificationreminder set reminderdate = now(), adminIP = '%s' where Id = 1", $_SERVER['REMOTE_ADDR']);
        Yii::$app->getDb()->createCommand($sql)->execute();
    }

    public static function checkAdminReminder() {
        $result = false;
        
        $sql = "select * from tbl_notificationreminder where reminderdate > subdate(now(), interval 1 day) and reminderdate < adddate(now(), interval 1 day) and Id = 1";
        $connection = Yii::$app->getDb();
        $reminder = $connection->createCommand($sql)->queryAll(); 

        if (count($reminder) > 0) {
            $result = true;
        }           
        return $result;
    }

    public static function resetAdminReminder() {
        $sql = sprintf("update tbl_notificationreminder set reminderdate = '' where Id = 1", $_SERVER['REMOTE_ADDR']);

        Yii::$app->getDb()->createCommand($sql)->execute();
    }

    public static function checkSunshadeReminder() {
        $result = false;
        
        $sql = "select * from tbl_notificationreminder where reminderdate > subdate(now(), interval 1 day) and reminderdate < adddate(now(), interval 1 day) and Id = 2";
        $connection = Yii::$app->getDb();
        $reminder = $connection->createCommand($sql)->queryAll(); 

        if (count($reminder) > 0) {
            $result = true;
        }           
        return $result;
    }

    public static function updateSunshadeReminder() {
        $sql = sprintf("update tbl_notificationreminder set reminderdate = now(), adminIP = '%s' where Id = 2", $_SERVER['REMOTE_ADDR']);
        Yii::$app->getDb()->createCommand($sql)->execute();
    }

    public static function resetSunshadeReminder() {
        $sql = sprintf("update tbl_notificationreminder set reminderdate = '' where Id = 2", $_SERVER['REMOTE_ADDR']);

        Yii::$app->getDb()->createCommand($sql)->execute();
    }
}
