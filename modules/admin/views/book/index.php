<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\BookSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i>Saved!</h4>
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>

<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>

    </p>

    <?= Html::beginForm(\yii\helpers\Url::to(['/admin/book/set-category']), 'post', ['class'=>'form-group']) ?>
    <div class="row">
        <div class="col-sm-3">
            <?= Html::dropDownList('cat', 'id', \app\models\Book::getListOfCategories(), [
                'prompt' => '-- Choose Category --',
                'class'=>'form-control'
            ]) ?>
        </div>
        <div class="col-sm-9">
            <?= Html::submitButton('Move into the category', ['class'=>'btn btn-warning']); ?>
            <?= Html::a('Create Book', ['create'], ['class' => 'btn btn-success pull-right']) ?>
        </div>
    </div>
    <br>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                // you may configure additional properties here
            ],
            'id',
            'name',
            'short_description:ntext',
            [
                'attribute' => 'category_id',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->category->name;
                }
            ],
            [
                'attribute' => 'author_id',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->author->name;
                }
            ],
            // 'author_id',
            // 'category_id',
            // 'preview',
            //'created_at:date',
            //'updated_at:date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>

    <?= Html::endForm() ?>
</div>
