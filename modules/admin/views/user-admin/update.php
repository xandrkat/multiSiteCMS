<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $formModel \app\core\adminMenu\MenuAdminForm */

$this->title = 'Изменение данных пользователя';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">
    <div class="row">
        <div class="col-md-6">
    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'formModel' => $formModel,
    ]) ?>
        </div>
    </div>
</div>
