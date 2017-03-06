<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Book */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="book-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'short_description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'full_description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'author_id')->dropDownList($model::getListOfAuthors()) ?>


    <?= $form->field($model, 'category_id')->dropDownList($model::getListOfCategories()) ?>

    <?= $form->field($model, 'filePreview')->fileInput() ?>

    <?php
    if(!empty($model->preview)) {
        echo Html::img('@web' . DIRECTORY_SEPARATOR . \app\models\UploadFile::GENERAL_UPLOAD_FOLDER .
            DIRECTORY_SEPARATOR . $model::FOLDER_FOR_IMAGE . DIRECTORY_SEPARATOR . $model->preview, [
            'class' => 'img-responsive img-thumbnail admin-preview',
        ]);

        echo Html::a('delete image', \yii\helpers\Url::to([
            '/admin/book/del-file',
            'id'=> $model->id,
            'file' => 'preview'
        ]), [
            'class' => 'del-link'
        ]);
    }
    ?>

    <div class="form-group">
        <?= $form->field($model, 'fileEpub')->fileInput() ?>
        <?php
        if(!empty($model->epub)) {
            echo 'Pdf: '.$model->epub.'&nbsp;&nbsp;&nbsp;';
            echo Html::a('delete epub', \yii\helpers\Url::to([
                '/admin/book/del-file',
                'id'=> $model->id,
                'file' => 'epub'
            ]), [
                'class' => 'del-link'
            ]);
        }
        ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'fileFb2')->fileInput() ?>
        <?php
        if(!empty($model->fb2)) {
            echo 'Pdf: '.$model->fb2.'&nbsp;&nbsp;&nbsp;';
            echo Html::a('delete fb2', \yii\helpers\Url::to([
                '/admin/book/del-file',
                'id'=> $model->id,
                'file' => 'fb2'
            ]), [
                'class' => 'del-link'
            ]);
        }
        ?>
    </div>
    <div class="form-group">
        <?= $form->field($model, 'fileDoc')->fileInput() ?>
        <?php
        if(!empty($model->doc)) {
            echo 'Pdf: '.$model->doc.'&nbsp;&nbsp;&nbsp;';
            echo Html::a('delete doc', \yii\helpers\Url::to([
                '/admin/book/del-file',
                'id'=> $model->id,
                'file' => 'doc'
            ]), [
                'class' => 'del-link'
            ]);
        }
        ?>
    </div>
    <div class="form-group">
        <?= $form->field($model, 'fileTxt')->fileInput() ?>
        <?php
        if(!empty($model->txt)) {
            echo 'Pdf: '.$model->txt.'&nbsp;&nbsp;&nbsp;';
            echo Html::a('delete txt', \yii\helpers\Url::to([
                '/admin/book/del-file',
                'id'=> $model->id,
                'file' => 'txt'
            ]), [
                'class' => 'del-link'
            ]);
        }
        ?>
    </div>
    <div class="form-group">
        <?= $form->field($model, 'filePdf')->fileInput() ?>
        <?php
        if(!empty($model->pdf)) {
            echo 'Pdf: '.$model->pdf.'&nbsp;&nbsp;&nbsp;';
            echo Html::a('delete pdf', \yii\helpers\Url::to([
                '/admin/book/del-file',
                'id'=> $model->id,
                'file' => 'pdf'
            ]), [
                'class' => 'del-link'
            ]);
        }
        ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
