<?php

namespace app\modules\filemanager\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /** @var UploadedFile[] */
    public $files;
    /** @var string */
    public $path;

    /** @inheritdoc */
    public function rules()
    {
        return [
            ['path', 'required'],
            ['files', 'file', 'skipOnEmpty' => false, 'maxFiles' => 10]
        ];
    }

    /**
     * @return bool
     */
    public function upload()
    {
        $directory = Directory::createByPath($this->path);

        if ($this->validate()) {
            foreach ($this->files as $file) {
                $file->saveAs($directory->fullPath . $file->baseName . '.' . $file->extension);
            }
            return true;
        } else {
            return false;
        }
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'files' => \Yii::t('filemanager', 'Files')
        ];
    }

    /**
     * @param $attribute
     */
    public function checkPath($attribute)
    {
        $directory = Directory::createByPath($this->$attribute);

        if (!$directory->isExist) {
            $this->addError($attribute, \Yii::t('filemanager', 'Is set to nonexistent path.'));
        } elseif (is_file($directory->fullPath)) {
            $this->addError($attribute, \Yii::t('filemanager', 'On the specified path there is a file.'));
        }
    }
}