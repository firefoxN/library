<?php

namespace app\models;


use Yii;
use yii\base\Model;
use yii\helpers\BaseFileHelper;
use yii\helpers\Url;
use yii\web\UploadedFile;

class UploadFile extends Model
{
    const GENERAL_UPLOAD_FOLDER = 'uploads';

    public $objFile;
    protected $generalUploadFolder = 'uploads';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['objFile'], 'required'],
            [['objFile'], 'file', 'extensions' => ['png', 'jpg', 'gif', 'epub', 'doc', 'docx', 'txt', 'pdf', 'fb2']]
        ];
    }

    /**
     * Uploads the transferred file to the server
     *
     * @param UploadedFile $file
     * @param string       $oldFile   name of old file with subdirectory, which we should delete (delete file, not
     *                                directory), if empty nothing will delete
     * @param string       $directory subdirectory of directory "uploads" for uploading, if empty will upload
     *                                directly to folder "uploads"
     * @param string       $generalUploadFolder name of base folder from root for uploading file
     * @param int          $perm permission for file and subfolder
     *
     * @return string|false name of file with extension
     * @throws \yii\base\Exception
     */
    public function uploadFile(UploadedFile $file, $oldFile='', $directory='', $generalUploadFolder='', $perm = 0775)
    {
        $this->objFile = $file;

        if(!empty($this->objFile) && $this->validate()) {
            $this->setGeneralUploadFolder($generalUploadFolder);
            $filename = $this->generateFileName();

            //delete the old file if necessary
            $this->deleteFile($oldFile);

            //path of directory for saving
            $generalUploadFolder == '' ? $baseFolder = self::GENERAL_UPLOAD_FOLDER : $baseFolder = $generalUploadFolder;

            $directory == '' ? $foldersPath = $baseFolder . DIRECTORY_SEPARATOR : $foldersPath = $baseFolder.
                DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR;


            BaseFileHelper::createDirectory(Yii::getAlias('@web') . $foldersPath, $perm);

            $this->objFile->saveAs(Yii::getAlias('@web') . $foldersPath . $filename);
            chmod(Yii::getAlias('@web') . $foldersPath . $filename, $perm);

            return $filename;
        }
    }

    /**
     * @param string $oldFileName full path for file
     */
    public function deleteFile($oldFileName)
    {
        clearstatcache();
        if(!empty($oldFileName) && file_exists($oldFileName)) {
            unlink($oldFileName);
        }
    }

    /**
     * @return string path to main folder for uploading
     */
    private function getFolder()
    {
        return Yii::getAlias('@web') . self::GENERAL_UPLOAD_FOLDER . DIRECTORY_SEPARATOR;
    }

    /**
     * @return string name of loading file
     */
    private function generateFileName()
    {
        return $this->objFile->baseName . '_' . time() . '.' . $this->objFile->extension;
    }

    /**
     * Set name of main folder for uploads
     *
     * @param string $nameFolder
     */
    private function setGeneralUploadFolder($nameFolder)
    {
        if(!empty($nameFolder)) {
            $this->generalUploadFolder = $nameFolder;
        }
    }
}