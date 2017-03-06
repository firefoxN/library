<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Book */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'slug',
            'short_description:ntext',
            'full_description:ntext',
            [
                'attribute'=>'author_id',
                'value'=>$model->author->name,
                'format' => 'raw',
            ],
            [
                'attribute'=>'category_id',
                'value'=>$model->category->name,
                'format' => 'raw',
            ],
            [
                'attribute'=>'preview',
                'value'=>DIRECTORY_SEPARATOR . $model->getFullPathOfPreview(),
                'format' => ['image',['width'=>'100']],
            ],
            [
                'attribute' => 'pdf',
                'value' => function ($model) {
                    if(!empty($model->pdf)) {
                        return Html::a('Download', DIRECTORY_SEPARATOR . $model->getFullPathOfFile('pdf'), ['target'=>'blank']);
                    } else {
                        return 'No file';
                    }
                },
                'format'=>'raw',
            ],
            [
                'attribute' => 'doc',
                'value' => function ($model) {
                    if(!empty($model->doc)) {
                        return Html::a('Download', DIRECTORY_SEPARATOR . $model->getFullPathOfFile('doc'),
                        ['target'=>'blank']);
                    } else {
                        return 'No file';
                    }
                },
                'format'=>'raw',
            ],
            [
                'attribute' => 'fb2',
                'value' => function ($model) {
                    if(!empty($model->fb2)) {
                        return Html::a('Download', DIRECTORY_SEPARATOR . $model->getFullPathOfFile('fb2'), ['target'=>'blank']);
                    } else {
                        return 'No file';
                    }
                },
                'format'=>'raw',
            ],
            [
                'attribute' => 'txt',
                'value' => function ($model) {
                    if(!empty($model->txt)) {
                        return Html::a('Download', DIRECTORY_SEPARATOR . $model->getFullPathOfFile('txt'), ['target'=>'blank']);
                    } else {
                        return 'No file';
                    }
                },
                'format'=>'raw',
            ],
            [
                'attribute' => 'epub',
                'value' => function ($model) {
                    if(!empty($model->epub)) {
                        return Html::a('Download', DIRECTORY_SEPARATOR . $model->getFullPathOfFile('epub'), ['target'=>'blank']);
                    } else {
                        return 'No file';
                    }
                },
                'format'=>'raw',
            ],
            'created_at:date',
            'updated_at:date',
        ],
    ]) ?>

</div>
