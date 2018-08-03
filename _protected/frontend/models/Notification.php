<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tbl_notification".
 *
 * @property integer $Id
 * @property string $username
 * @property string $title
 * @property string $title_it
 * @property string $sunshadeseat
 * @property string $readstate
 * @property string $create_date
 * @property integer $lookupid
 */
class Notification extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_notification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lookupid'], 'integer'],
            [['username', 'create_date'], 'string', 'max' => 50],
            [['title', 'title_it'], 'string', 'max' => 255],
            [['sunshadeseat', 'readstate'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => Yii::t('messages', 'ID'),
            'username' => Yii::t('messages', 'Username'),
            'title' => Yii::t('messages', 'Title'),
            'title_it' => Yii::t('messages', 'Title It'),
            'sunshadeseat' => Yii::t('messages', 'Sunshadeseat'),
            'readstate' => Yii::t('messages', 'Readstate'),
            'create_date' => Yii::t('messages', 'Create Date'),
            'lookupid' => Yii::t('messages', 'Lookupid'),
        ];
    }

    public static function saveNewNotification($title, $title_it, $username, $id, $sunshadeseat) {
        date_default_timezone_set("Europe/Rome");

        $sql = sprintf("INSERT tbl_notification (lookupid, username, title, title_it, sunshadeseat, readstate, create_date) VALUES (%d, '%s', '%s', '%s', '%s', 'unread', '%s')", $id, $username,  $title, $title_it, $sunshadeseat, date('Y-m-d H:i:s'));

        Yii::$app->getDb()->createCommand($sql)->execute();
    }

    public static function getNofications($lang){
        $sql = sprintf("SELECT t1.Id as notiId, t1.sunshadeseat as sunshade, t1.username, t1.lookupid as lookupId, t2.bookinfo_id as sunshadeId, t2.book_id as bookId, t2.guest_id as guestId, if('%s' = 'en', title, title_it) as title from tbl_notification as t1 join tbl_book_lookup as t2 on t1.lookupid = t2.id where t1.readstate='unread';", $lang);
        $notification = Yii::$app->getDb()->createCommand($sql)->queryAll();

        return $notification;
    }

    public static function updateNotification($arrayOfId, $readState = "read") {
        $sql = "DELETE FROM tbl_notification where Id in ($arrayOfId)";
        Yii::$app->getDb()->createCommand($sql)->execute();

        return $sql;
    }

    public static function eraseNotification($selection){
        $sql = "DELETE FROM tbl_notification where lookupid in ($selection);";
        Yii::$app->getDb()->createCommand($sql)->execute();
    }
}
