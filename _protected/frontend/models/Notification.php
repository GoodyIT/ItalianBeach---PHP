<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tbl_notification".
 *
 * @property integer $Id
 * @property string $title
 * @property string $title_it
 * @property string $sunshadeseat
 * @property string $readstate
 * @property string $create_date
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
            [['title', 'title_it'], 'string', 'max' => 255],
            [['sunshadeseat', 'readstate'], 'string', 'max' => 11],
            [['create_date'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => Yii::t('messages', 'ID'),
            'title' => Yii::t('messages', 'Title'),
            'title_it' => Yii::t('messages', 'Title It'),
            'sunshadeseat' => Yii::t('messages', 'Sunshadeseat'),
            'readstate' => Yii::t('messages', 'Readstate'),
            'create_date' => Yii::t('messages', 'Create Date'),
        ];
    }

     public static function saveNewNotification($title, $title_it, $sunshadeseat) {
        date_default_timezone_set("UTC");
        $notification = new Notification();
        $notification->title = $title;
        $notification->title_it = $title_it;
        $notification->sunshadeseat = $sunshadeseat;
        $notification->readstate = "unread";
        $notification->create_date = date('Y-m-d H:i:s');
        $notification->save(false);
    }

    public static function getNofications($lang){
        $sql = sprintf("select t2.Id, t1.Id as notiId, if('%s' = 'en', t1.title, t1.title_it) as title , t1.sunshadeseat, concat(t3.firstname, ' ', t3.lastname) as username from tbl_notification as t1 join tbl_bookinfo as t2 on t1.sunshadeseat = t2.seat join tbl_guest as t3 on t2.guestId = t3.Id join tbl_book as t4 on t2.bookId = t4.Id where t1.readstate = 'unread' order by t1.create_date", $lang);

        $connection = Yii::$app->getDb();
        $notification = $connection->createCommand($sql)->queryAll();

        return $notification;
    }

    public static function updateNotification($arrayOfId, $readState = "read") {
        $sql = sprintf("update tbl_notification set readstate='%s' where Id in (%s)", $readState, $arrayOfId);
        $connection = Yii::$app->getDb();
       $notification = $connection->createCommand($sql)->execute();

        return $sql;
    }
}
