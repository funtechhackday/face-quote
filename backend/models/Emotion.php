<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "emotion".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Quote[] $quotes
 */
class Emotion extends \yii\db\ActiveRecord
{

    /**
     * Default emotion is neutral in case there is no face in photo
     */
    const DEFAULT_EMOTION_ID = 7;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'emotion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuotes()
    {
        return $this->hasMany(Quote::className(), ['emotion_id' => 'id']);
    }

}
