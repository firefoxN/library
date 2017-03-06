<?php

use yii\widgets\ListView;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php echo $this->render('_alfabet'); ?>
            </div>
        </div>

    </div>
</div>
<div class="site-category">
    <div class="row">
        <div class="col-xs-12">
            <div class="well">
                <?= HtmlPurifier::process($category->full_description) ?>
            </div>
        </div>
    </div>
    <?php
    echo ListView::widget([
        'dataProvider' => $booksProvider,
        'itemView' => '_list',
    ]);
    ?>
</div>
