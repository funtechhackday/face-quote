<?php

namespace app\commands;

use app\models\Emotion;
use app\models\Quote;
use yii\console\Controller;
use yii\console\Exception;

class ParseController extends Controller
{

    /**
     * Parses quotes
     *
     * ./yii parse/quotes https://citaty.info/tema/zlost angry 31
     * ./yii parse/quotes https://citaty.info/tema/otvrashenie disgust 6
     * ./yii parse/quotes https://citaty.info/tema/strah fear 30
     * ./yii parse/quotes https://citaty.info/tema/smeshnye-citaty happy 30
     * ./yii parse/quotes https://citaty.info/tema/chernyi-yumor sad 13
     * ./yii parse/quotes https://citaty.info/tema/neozhidannost surprise 9
     * ./yii parse/quotes https://citaty.info/tema/mysli neutral 30
     *
     * @param null $url
     * @param null $emotion
     * @param null $maxPage
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function actionQuotes($url = null, $emotion = null, $maxPage = null, $length = 140)
    {
        if(!$url) throw new Exception("url can not be empty");

        $mEmotion = Emotion::findOne(['name' => $emotion]);
        if(!$mEmotion) throw new Exception("emotion $emotion not found");

        if(!$maxPage) throw new Exception("maxPage can not be empty");

        $quotes = Quote::getByUrl($url, $maxPage, $length);

        foreach($quotes as $quote) {

            $mQuote = new Quote([
                'text' => $quote,
                'emotion_id' => $mEmotion->id
            ]);

            if(!$mQuote->save()) throw new \yii\db\Exception("Unable to save Quote model");

        }
    }

}
