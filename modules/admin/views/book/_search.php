<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\BookSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="book-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>


    <? //echo $form->field($model, 'name') ?>

    <? //echo $form->field($model, 'short_description') ?>

    <? //echo$form->field($model, 'full_description') ?>

    <?php echo $form->field($model, 'author_id')->dropDownList(\app\models\Book::getListOfAuthors(),
        ['prompt'=>'Choose author']) ?>

    <?php echo $form->field($model, 'category_id')->dropDownList(\app\models\Book::getListOfCategories(), [
        'prompt' => 'Choose category'
    ]) ?>

    <?php // echo $form->field($model, 'preview') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
