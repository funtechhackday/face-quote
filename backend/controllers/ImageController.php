<?php

namespace app\controllers;

use app\models\Emotion;
use app\models\ImageProcessor;
use app\models\Quote;
use Yii;
use app\models\UploadForm;
use yii\rest\Controller;
use yii\web\UploadedFile;

class ImageController extends Controller
{
    public function actionUpload()
    {
        $model = new UploadForm();

        if (\Yii::$app->request->isPost) {

            $model->imageFile = UploadedFile::getInstanceByName('imageFile');
            $imageName = time() . '_' . \Yii::$app->security->generateRandomString(6);

            if ($model->upload($imageName)) {

                // file is uploaded successfully

                $imagePath = \Yii::getAlias('@webroot') . "/upload/$imageName";

                $emotionId = ImageProcessor::getEmotionId($imagePath);
                if(!$emotionId) $emotionId = Emotion::DEFAULT_EMOTION_ID;

                return [
                    'quote' => Quote::getRandomByEmotion($emotionId),
                    'status' => 'success'
                ];
            }
        }

        \Yii::$app->response->setStatusCode(400);
        return ['status' => 'failed'];
    }
}
