<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Author */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="author-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'short_description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'full_description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'filePreview')->fileInput() ?>

    <?php
    if(!empty($model->preview)) {
        echo Html::img('@web' . DIRECTORY_SEPARATOR . \app\models\UploadFile::GENERAL_UPLOAD_FOLDER .
            DIRECTORY_SEPARATOR . $model::FOLDER_FOR_IMAGE . DIRECTORY_SEPARATOR . $model->preview, [
            'class' => 'img-responsive img-thumbnail',
        ]);

        echo Html::a('delete image', \yii\helpers\Url::to([
            '/admin/author/del-img',
            'id'=> $model->id,
        ]), [
            'class' => 'del-link'
        ]);
    }
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
