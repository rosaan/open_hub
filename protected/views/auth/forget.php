<?php
$this->pageTitle = Yii::t('app', 'Forgot Password');
$this->breadcrumbs = array(
    Yii::t('app', 'Forgot Password'),
);
?>

<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => 'form-login',
    'htmlOptions' => array(
        'class' => 'm-t',
        'role' => 'form'
    )
)); ?>
<div class="middle-box text-center animated fadeInDown w-full" style="max-width: 480px">

    <div class="ibox">
        <div class="ibox-content">

            <h3>Forgot Password?</h3>
            <p>You can reset your password here.</p>

            <div class="form-group <?= $form->bsError($model, 'username') ? 'has-error' : '' ?>">
                <?= $form->bsEmailTextField($model, 'username', array('placeholder' => Yii::t('app', 'Email'), 'type' => 'email', 'required' => true)); ?>
                <span class="text-left"><?= $form->bsError($model, 'username'); ?></span>
            </div>

            <button type="submit" class="btn btn-primary full-width m-b">Reset Password</button>

        </div>
    </div>
</div>
<?php $this->endWidget(); ?>