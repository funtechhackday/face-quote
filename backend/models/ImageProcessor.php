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

        $daceDetectorScriptPath = \Yii::$app->params['faceDetectorScriptPath'];

        $command = "python $daceDetectorScriptPath $imagePath";

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
