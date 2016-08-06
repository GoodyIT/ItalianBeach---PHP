<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tbl_servicetype".
 *
 * @property integer $Id
 * @property string $servicename
 * @property string $servicename_it
 *
 * @property TblPrice[] $tblPrices
 */
class Servicetype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_servicetype';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['servicename', 'servicename_it'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => Yii::t('messages', 'ID'),
            'servicename' => Yii::t('messages', 'Servicename'),
            'servicename_it' => Yii::t('messages', 'Servicename It'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblPrices()
    {
        return $this->hasMany(TblPrice::className(), ['servicetype_Id' => 'Id']);
    }

    public static function getAllInfo($lang = 'en') {
        $lists =  static::find()->asArray()->all();
        $results = [];
        foreach ($lists as $key => $value) {
            if ($lang == 'it') {
                $results[$value['Id']] = $value['servicename_it'];
            } else {
                $results[$value['Id']] = $value['servicename'];
            }
        }

        return $results;
    }
}
