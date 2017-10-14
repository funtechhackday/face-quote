<?php

namespace app\models;

use Sunra\PhpSimple\HtmlDomParser;
use Yii;
use yii\db\Exception;
use yii\db\Expression;

/**
 * This is the model class for table "quote".
 *
 * @property integer $id
 * @property string $text
 * @property integer $emotion_id
 *
 * @property Emotion $emotion
 */
class Quote extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'quote';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text'], 'string'],
            [['emotion_id'], 'integer'],
            [['emotion_id'], 'exist', 'skipOnError' => true, 'targetClass' => Emotion::className(), 'targetAttribute' => ['emotion_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Text',
            'emotion_id' => 'Emotion ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmotion()
    {
        return $this->hasOne(Emotion::className(), ['id' => 'emotion_id']);
    }

    /**
     * Returns quotes array by url
     *
     * @param $url
     * @param $maxPage
     * @param int $length
     * @return array
     */
    public static function getByUrl($url, $maxPage, $length = 140) {

        $res = [];
        $maxPageToShow = $maxPage - 1;

        for($i = 0; $i < $maxPage; $i++){

            echo "Parsing page $i/$maxPageToShow\n";

            $urlWithPage = "$url?page=$i";

            $html = HtmlDomParser::file_get_html($urlWithPage);

            // find all quote blocks
            $quoteRows = $html->find('.field-name-body');

            foreach($quoteRows as $quoteRow) {

                $text = self::clean($quoteRow->plaintext);

                if(strlen($text) > $length) continue;

                $res[] = $text;

            }

        }

        return $res;
    }

    /**
     * Cleans string from special characters
     *
     * @param $str
     * @return mixed|string
     */
    public static function clean($str) {

        $str = trim($str);
        $str = str_replace("&nbsp;", " ", $str);
        $str = str_replace("&mdash;", "—", $str);
        $str = str_replace("&lt;", "<", $str);
        $str = str_replace("&gt;", ">", $str);

        return $str;
    }

    /**
     * Returns random quote
     *
     * @return mixed|null
     */
    public static function getRandom() {

        $emotionId = rand(1, 7);

        $mQuote = self::find()->where(['emotion_id' => $emotionId])->orderBy(new Expression('rand()'))->limit(1)->one();

        return $mQuote ? $mQuote->text : null;
    }

    /**
     * Returns random quote by emotion
     *
     * @param $emotionId
     * @return mixed|null
     * @throws Exception
     */
    public static function getRandomByEmotion($emotionId) {

        $mEmotion = Emotion::findOne($emotionId);
        if(!$mEmotion) throw new Exception("emotion $emotionId not found");

        $mQuote = self::find()->where(['emotion_id' => $mEmotion->id])->orderBy(new Expression('rand()'))->limit(1)->one();

        return $mQuote ? $mQuote->text : null;
    }

}
