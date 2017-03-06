<?php

use yii\widgets\ListView;

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
<div class="books">
    <?php
    echo ListView::widget([
        'dataProvider' => $booksProvider,
        'itemView' => '_list',
    ]);
    ?>
</div>
