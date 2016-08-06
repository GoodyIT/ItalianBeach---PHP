<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tbl_rowrestriction".
 *
 * @property string $rowId
 * @property string $servicetype
 */
class Rowrestriction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_rowrestriction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rowId'], 'required'],
            [['rowId'], 'string', 'max' => 11],
            [['servicetype'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rowId' => Yii::t('app', 'RowID'),
            'servicetype' => Yii::t('app', 'Servicetype'),
        ];
    }

    public static function getAll() {
        $lists =  static::find()->asArray()->all();
        $results = [];
        foreach ($lists as $key => $value) {
            $array = explode(",",$value['servicetype']);
            $newArray = [];
            foreach ($array as $subkey => $subvalue) {
                $newArray[$subvalue]=$subvalue;
            }
            $results[$value['rowId']] = $newArray;
        }

        return $results;
    }

    public static function addServicetype($row, $servicetype) {
        $sql = sprintf("select * from tbl_rowrestriction where rowId = '%s' ", $row);
        $rowrestriction = Yii::$app->getDb()->createCommand($sql)->queryAll();

        return $rowrestriction;
        $sql = sprintf("update tbl_rowrestriction set servicetype='%s' where rowId='%s'", $rowrestriction[0]['servicetype'].','.$servicetype);

        Yii::$app->getDb()->createCommand($sql)->execute();
    }
}
