<?php
/**
 * @var $image \app\core\products\forms\ProductImageForm
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
?>

<?php Pjax::begin([
    'enablePushState' => false,
]) ?>
<?php $form = ActiveForm::begin([
    'action' => Url::to(['update-image', 'id' => $image->id]),
    'options' => [
        'data-pjax' => ''
    ]
]); ?>
<p><?= $form->field($image, 'alt') ?></p>
<?= $form->field($image, 'title_link') ?>
<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
</div>
<?php ActiveForm::end(); ?>
<?php Pjax::end() ?>
