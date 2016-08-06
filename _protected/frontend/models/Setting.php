<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tbl_setting".
 *
 * @property integer $Id
 * @property string $propertytitle
 * @property string $footertitle
 * @property string $phonenumber
 * @property string $email
 */
class Setting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['propertytitle', 'email'], 'string', 'max' => 50],
            [['footertitle'], 'string', 'max' => 255],
            [['phonenumber'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => Yii::t('messages', 'ID'),
            'propertytitle' => Yii::t('messages', 'Propertytitle'),
            'footertitle' => Yii::t('messages', 'Footertitle'),
            'phonenumber' => Yii::t('messages', 'Phonenumber'),
            'email' => Yii::t('messages', 'Email'),
        ];
    }
}
