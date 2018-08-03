<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tbl_pending_guest".
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
 * @property string $state
 * @property string $token
 * @property string $registered_at
 */
class PendingGuest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_pending_guest';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['firstname', 'lastname', 'email'], 'required'],
            [['firstname', 'lastname', 'email', 'city', 'zipcode', 'registered_at'], 'string', 'max' => 100],
            [['country', 'address', 'state'], 'string', 'max' => 50],
            [['phonenumber'], 'string', 'max' => 20],
            [['token'], 'string', 'max' => 255],
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
            'state' => Yii::t('messages', 'State'),
            'token' => Yii::t('messages', 'Token'),
            'registered_at' => Yii::t('messages', 'Registered At'),
        ];
    }

    public function saveInfo()
    {
        $token = trim($this->token);
        $email = trim($this->email);
        $this->registered_at = date('Y-m-d H:i:s');
        $sql = "SELECT Id FROM tbl_pending_guest WHERE email='$email' and token='$token'";
        $result = Yii::$app->getDb()->createCommand($sql)->queryAll();
        if (empty($result)) {
            return $this->save();
        } else {
            $date = date('Y-m-d H:i:s');
            $sql = "UPDATE tbl_pending_guest SET firstname='$this->firstname', lastname='$this->lastname', country='$this->country', city='$this->city', phonenumber='$this->phonenumber', zipcode='$this->zipcode', registered_at='$date' WHERE email='$email' and token='$token'";
            Yii::$app->getDb()->createCommand($sql)->execute();
        }
    }

    public static function getGuestInfo($token)
    {
        $sql = "SELECT * from tbl_pending_guest where token='$token'";
        return Yii::$app->getDb()->createCommand($sql)->queryOne();
    }

    public static function clearGuest($token)
    {
        $sql = "DELETE FROM tbl_pending_guest WHERE token='$token'";
        return Yii::$app->getDb()->createCommand($sql)->execute();
    }
}
