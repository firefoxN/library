<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "author".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $short_description
 * @property string $full_description
 * @property string $preview
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Book[] $books
 */
class Author extends ActiveRecord
{
    const FOLDER_FOR_IMAGE = 'authors';

    public $filePreview;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'author';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['short_description', 'full_description'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['name', 'slug', 'preview'], 'string', 'max' => 255],
            [['filePreview'], 'file', 'extensions' => ['png', 'jpg', 'gif'], 'maxSize' => 1024*1024*2],
            [['slug'], 'unique'],
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

        return parent::beforeDelete();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooks()
    {
        return $this->hasMany(Book::className(), ['author_id' => 'id']);
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

    public function getFullPathOfPreview()
    {
        $path = Yii::getAlias('@web' . DIRECTORY_SEPARATOR . 'uploads' .
            DIRECTORY_SEPARATOR . self::FOLDER_FOR_IMAGE . DIRECTORY_SEPARATOR . $this->preview);

        return $path;
    }
}
