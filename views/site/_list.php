<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>

<div class="news-item">
    <h2><?= Html::encode($model->name) ?></h2>
    <h5 class="text-right"><?=$model->author->name?></h5>
    <?= HtmlPurifier::process($model->short_description) ?>
    <?= Html::a('Read more', \yii\helpers\Url::to(['site/book', 'slug'=>$model->slug])) ?>
</div>