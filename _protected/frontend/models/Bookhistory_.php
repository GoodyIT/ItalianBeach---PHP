<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tbl_bookhistory".
 *
 * @property integer $Id
 * @property integer $guestId
 * @property string $historyDate
 * @property string $historyTitle
 * @property string $historyIP
 * @property string $sunshadeseat
 * @property integer $bookId
 * @property integer $price
 * @property integer $guests
 */
class Bookhistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_bookhistory';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['guestId', 'bookId'], 'required'],
            [['guestId', 'bookId', 'price', 'guests'], 'integer'],
            [['historyDate', 'historyTitle', 'historyIP'], 'string', 'max' => 20],
            [['sunshadeseat'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => Yii::t('messages', 'ID'),
            'guestId' => Yii::t('messages', 'Guest ID'),
            'historyDate' => Yii::t('messages', 'History Date'),
            'historyTitle' => Yii::t('messages', 'History Title'),
            'historyIP' => Yii::t('messages', 'History Ip'),
            'sunshadeseat' => Yii::t('messages', 'Sunshadeseat'),
            'bookId' => Yii::t('messages', 'Book ID'),
            'price' => Yii::t('messages', 'Price'),
            'guests' => Yii::t('messages', 'Guests'),
        ];
    }

    public static function saveHistory($guestId, $historyTitle, $sunshadeseat, $bookId, $price, $guests, $historyIP) {
        date_default_timezone_set("UTC");
        $bookHistory = new Bookhistory();

        $bookHistory->guestId = $guestId;
        $bookHistory->historyTitle = $historyTitle;
        $bookHistory->sunshadeseat = $sunshadeseat;
        $bookHistory->bookId = $bookId;
        $bookHistory->price = $price;
        $bookHistory->guests = $guests;
        $bookHistory->historyIP = $historyIP;
        $bookHistory->historyDate = date('Y-m-d H:i:s');

        $bookHistory->save(false);
    }

    
}
