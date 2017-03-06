<?php

namespace app\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $short_description
 * @property string $full_description
 * @property int $author_id
 * @property int $category_id
 * @property string $preview
 * @property string $epub
 * @property string $txt
 * @property string $pdf
 * @property string $doc
 * @property string $fb2
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Author $author
 * @property Category $category
 */
class Book extends \yii\db\ActiveRecord
{
    const FOLDER_FOR_IMAGE = 'books';
    const FOLDER_FOR_FILES = 'files';

    public $filePreview;
    public $fileEpub;
    public $fileFb2;
    public $fileTxt;
    public $fileDoc;
    public $filePdf;
    public $resArr=array();
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'book';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'category_id'], 'required'],
            [['short_description', 'full_description'], 'string'],
            [['author_id', 'category_id', 'created_at', 'updated_at'], 'integer'],
            [['name', 'slug', 'preview', 'epub', 'txt', 'pdf', 'doc', 'fb2'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            [['filePreview'], 'file', 'extensions' => ['png', 'jpg', 'gif'], 'maxSize' => 1024*1024*2],
            [['fileEpub'], 'file', 'extensions' => ['epub']],
            [['fileFb2'], 'file', 'extensions' => ['fb2']],
            [['fileTxt'], 'file', 'extensions' => ['txt']],
            [['fileDoc'], 'file', 'extensions' => ['doc', 'docx']],
            [['filePdf'], 'file', 'extensions' => ['pdf']],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::className(), 'targetAttribute' => ['author_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'slug' => 'Slug',
            'short_description' => 'Short Description',
            'full_description' => 'Full Description',
            'author_id' => 'Author ID',
            'category_id' => 'Category ID',
            'preview' => 'Preview',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
            'slug' => [
                'class' => 'app\behaviors\Slug',
                'in_attribute' => 'name',
                'out_attribute' => 'slug',
                'translit' => true
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        $objUploadImage = new UploadFile();
        $objUploadImage->deleteFile(self::FOLDER_FOR_IMAGE . '/' . $this->preview);
        $objUploadImage->deleteFile(self::FOLDER_FOR_FILES . DIRECTORY_SEPARATOR . $this->pdf);
        $objUploadImage->deleteFile(self::FOLDER_FOR_FILES . DIRECTORY_SEPARATOR . $this->epub);
        $objUploadImage->deleteFile(self::FOLDER_FOR_FILES . DIRECTORY_SEPARATOR . $this->txt);
        $objUploadImage->deleteFile(self::FOLDER_FOR_FILES . DIRECTORY_SEPARATOR . $this->doc);
        $objUploadImage->deleteFile(self::FOLDER_FOR_FILES . DIRECTORY_SEPARATOR . $this->fb2);

        return parent::beforeDelete();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::className(), ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * Get list of categories for dropdown list
     *
     * @return array formatted array for dropdown list
     */
    public static function getListOfCategories()
    {
        $categories = Category::find()->orderBy('parent_id')->asArray()->all();
        $arrCat = ArrayHelper::map($categories, 'id', 'name', 'parent_id');

        $arrForRes = array();
        $arrRes = self::makeTree($arrCat, $arrForRes, 0, 1);

        return $arrRes;
    }

    /**
     * Get list of authors for dropdown list
     *
     * @return array
     */
    public static function getListOfAuthors()
    {
        $droptions = Author::find()->all();

        return ArrayHelper::map($droptions, 'id', 'name');
    }

    /**
     * Get the string with preview path
     *
     * @return string
     */
    public function getPathForPreview()
    {
        return self::FOLDER_FOR_IMAGE . DIRECTORY_SEPARATOR . $this->preview;
    }

    public function getPathForFile($attribute)
    {
        return self::FOLDER_FOR_FILES . DIRECTORY_SEPARATOR . $this->$attribute;
    }

    public function getFullPathOfPreview()
    {
        $path = Yii::getAlias('@web') . UploadFile::GENERAL_UPLOAD_FOLDER .
            DIRECTORY_SEPARATOR . self::FOLDER_FOR_IMAGE . DIRECTORY_SEPARATOR . $this->preview;

        return $path;
    }

    /**
     * @param string $attribute
     *
     * @return bool|string
     */
    public function getFullPathOfFile($attribute)
    {
        $path = Yii::getAlias('@web') . self::FOLDER_FOR_FILES .
            DIRECTORY_SEPARATOR . $this->$attribute;

        return $path;
    }

    /**
     * @param array   $arrCat
     * @param array   $arrRes
     * @param int     $parent_id default 0
     * @param int     $level default 1
     *
     * @return array
     */
    private static function makeTree($arrCat, &$arrRes, $parent_id=0, $level=1)
    {
        $prefics = '';
        for($i=0; $i < $level; $i++) {
            $prefics .= '-';
        }

        if(isset($arrCat[$parent_id])) {
            foreach($arrCat[$parent_id] as $catId=>$catName) {
                $arrRes[$catId] = $prefics . $catName;
                if(isset($arrCat[$catId])) {
                    self::makeTree($arrCat, $arrRes, $catId, ++$level);
                }
            }
        }

        return $arrRes;
    }
}
