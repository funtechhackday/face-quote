<?php

namespace app\models;

class ImageProcessor
{

    /**
     * Returns emotion id by image
     *
     * @param $imagePath
     * @return int|null
     */
    public static function getEmotionId($imagePath) {

        $faceDetectorScriptPath = \Yii::$app->params['faceDetectorScriptPath'];
        $faceDetectorScriptFolder = str_replace('image_emotion_detector.py', '', $faceDetectorScriptPath);

        $command = "cd $faceDetectorScriptFolder; python $faceDetectorScriptPath $imagePath";

        $output = shell_exec($command);

        foreach(Emotion::find()->all() as $mEmotion) {

            $pos = strpos($output, $mEmotion->name);

            // if emotion found in text output
            if($pos !== false) {
                return $mEmotion->id;
            }
        }

        return null;
    }

}
