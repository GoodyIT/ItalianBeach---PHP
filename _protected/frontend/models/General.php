<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tbl_general".
 *
 * @property integer $Id
 * @property string $token
 * @property string $searchfrom
 * @property string $searchto
 * @property string $chartsearchfrom
 * @property string $chartsearchto
 * @property string $registered_at
 */
class General extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_general';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['token', 'searchfrom', 'searchto', 'chartsearchfrom', 'chartsearchto', 'registered_at'], 'string', 'max' => 255],
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
            'searchfrom' => Yii::t('messages', 'Searchfrom'),
            'searchto' => Yii::t('messages', 'Searchto'),
            'chartsearchfrom' => Yii::t('messages', 'Chartsearchfrom'),
            'chartsearchto' => Yii::t('messages', 'Chartsearchto'),
            'registered_at' => Yii::t('messages', 'Registered At'),
        ];
    }

    public static function getFromTo($token)
    {
        $sql = "SELECT * from tbl_general WHERE token='$token'";
        return  Yii::$app->getDb()->createCommand($sql)->queryOne();
    }

    public static function saveSearchFromTo($token, $from, $to)
    {
        $sql = "SELECT * from tbl_general WHERE token='$token'";
        $model = Yii::$app->getDb()->createCommand($sql)->queryOne();
        $date = date('Y-m-d H:i:s');
        if (empty($model)) {
            $sql = "INSERT tbl_general  (token, chartsearchfrom, chartsearchto, registered_at) VALUES  ('$token', '$from', '$to', '$date')";
        } else
        {
            $sql = "UPDATE tbl_general SET chartsearchfrom='$from', chartsearchto='$to', registered_at='$date'";
        }

        return Yii::$app->getDb()->createCommand($sql)->execute();
    }

    public static function saveFromTo($token, $from, $to)
    {
        $sql = "SELECT * from tbl_general WHERE token='$token'";
        $model = Yii::$app->getDb()->createCommand($sql)->queryOne();
        $date = date('Y-m-d H:i:s');
        if (empty($model)) {
            $sql = "INSERT tbl_general  (token, searchfrom, searchto, registered_at) VALUES  ('$token', '$from', '$to', '$date')";
        } else
        {
            $sql = "UPDATE tbl_general SET searchfrom='$from', searchto='$to', registered_at='$date'";
        }

        return Yii::$app->getDb()->createCommand($sql)->execute();
    }

    public static function resetFromTo($token)
    {
        $sql = "UPDATE tbl_general SET searchfrom='', searchto='' WHERE token='$token'";
        return Yii::$app->getDb()->createCommand($sql)->execute();
    }
}
