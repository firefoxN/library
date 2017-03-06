<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

$this->title = $model->name;

$this->params['breadcrumbs'][] = ['label' => $model->category->name, 'url' => ['showCategory', 'id' =>
    $model->category->id]];
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="row">
    <div class="col-xs-12">
        <h1><?=$model->name ?></h1>
        <h5 class="text-right"><?=$model->author->name?></h5>
    </div>
    <div class="col-xs-12">
        <?php if(!empty($model->preview)) { ?>
            <?=Html::img(DIRECTORY_SEPARATOR . $model->getFullPathOfPreview(), ['style'=> 'max-width:20%;'])?>
        <?php } ?>
        <?= HtmlPurifier::process($model->full_description) ?>
    </div>
    <div class="col-xs-12">
        <dl class="dl-horizontal">
            <?php if(!empty($model->pdf)){ ?>
                <dt>PDF</dt>
                <dd>
                    <?= Html::a('Download file', \yii\helpers\Url::to(['site/sendFile', 'filename'=>$model->pdf]), [
                        'target'=> 'blank',
                    ])?>
                </dd>
            <?php } ?>
            <?php if(!empty($model->txt)){ ?>
                <dt>TXT</dt>
                <dd>
                    <?= Html::a('Download file', \yii\helpers\Url::to(['site/send-file', 'filename'=>$model->txt]), [
                        'target'=> 'blank',
                    ])?>
                </dd>
            <?php } ?>
            <?php if(!empty($model->epub)){ ?>
                <dt>Epub</dt>
                <dd>
                    <?= Html::a('Download file', \yii\helpers\Url::to(['site/sendFile', 'filename'=>$model->epub]))?>
                </dd>
            <?php } ?>
            <?php if(!empty($model->fb2)){ ?>
                <dt>FB2</dt>
                <dd>
                    <?= Html::a('Download file', \yii\helpers\Url::to(['site/sendFile', 'filename'=>$model->fb2]))?>
                </dd>
            <?php } ?>
            <?php if(!empty($model->doc)){ ?>
                <dt>DOC</dt>
                <dd>
                    <?= Html::a('Download file', \yii\helpers\Url::to(['site/sendFile', 'filename'=>$model->doc]))?>
                </dd>
            <?php } ?>
        </dl>
    </div>
</div>
