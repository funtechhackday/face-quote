<?php

namespace app\controllers;

use app\models\TestQuote;
use Yii;
use yii\web\Controller;

class SiteController extends Controller
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionFirstQuote()
    {
        $mTestQuote = TestQuote::find()->one();

        $text = null;
        if($mTestQuote) {
            $text = $mTestQuote->text;
            $mTestQuote->delete();
        }

        \Yii::$app->response->format = 'json';

        sleep(5);

        return [
            'quote' => $text,
            'emotion' => 'neutral',
            'status' => 'success'
        ];

    }

}
