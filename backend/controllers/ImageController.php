<?php

namespace app\controllers;

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
            if ($model->upload()) {
                // file is uploaded successfully
                return [
                    'quote' => Quote::getRandom(),
                    'status' => 'success'
                ];
            }
        }

        \Yii::$app->response->setStatusCode(400);
        return ['status' => 'failed'];
    }
}
