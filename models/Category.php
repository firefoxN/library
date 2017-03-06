<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $parent_id
 * @property string $short_description
 * @property string $full_description
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Book[] $books
 */
class Category extends \yii\db\ActiveRecord
{

    public $resArr=array(0=>'Родительская категория');
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parent_id', 'created_at', 'updated_at'], 'integer'],
            [['short_description', 'full_description'], 'string'],
            [['name', 'slug'], 'string', 'max' => 255],
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
            'parent_id' => 'Parent ID',
            'short_description' => 'Short Description',
            'full_description' => 'Full Description',
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
     * @return \yii\db\ActiveQuery
     */
    public function getBooks()
    {
        return $this->hasMany(Book::className(), ['category_id' => 'id']);
    }

    /**
     * If we wants to change parent of the current category, we can't change it to id of any of the descendants,
     * therefore we should exclude these nodes from array
     *
     * @return array formatted array for dropdownlist
     */
    public function getFormatedCategoryList()
    {
        $categories = Category::find()->orderBy('parent_id')->asArray()->all();
        $arrCat = ArrayHelper::map($categories, 'id', 'name', 'parent_id');

        $this->makeTree($arrCat, 0, 1, $this->id);

        return $this->resArr;
    }

    private function makeTree($arrCat, $parent_id=0, $level=1, $exeption='')
    {
        $prefics = '';
        for($i=0; $i < $level; $i++) {
            $prefics .= '-';
        }

        if(isset($arrCat[$parent_id])) {
            foreach($arrCat[$parent_id] as $catId=>$catName) {
                if($exeption != $catId) {
                    $this->resArr[$catId] = $prefics . $catName;
                    if(isset($arrCat[$catId])) {
                        $this->makeTree($arrCat, $catId, ++$level, $exeption);
                    }
                }

            }
        }
    }
}
