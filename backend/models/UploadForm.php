<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $imageName = time() . '_' . \Yii::$app->security->generateRandomString(6);
            $this->imageFile->saveAs('uploads/' . $imageName . '.' . $this->imageFile->extension);
            return true;
        } else {
            return false;
        }
    }

}
