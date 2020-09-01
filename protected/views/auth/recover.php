<?php
$this->pageTitle = Yii::t('app', 'Change Password');
$this->breadcrumbs = array(
    Yii::t('app', 'Change Password'),
);
?>

<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => 'form-recover-password',
    'htmlOptions' => array(
        'class' => 'm-t',
        'role' => 'form'
    )
)); ?>
<div class="middle-box text-center animated fadeInDown w-full" style="max-width: 480px">

    <div class="ibox">
        <div class="ibox-content">

            <h3>Change password</h3>
            <p>You can change your password here.</p>

            <div class="form-group <?= $form->bsError($model, 'password') ? 'has-error' : '' ?>">
                <?= $form->bsPasswordField($model, 'password', array('placeholder' => Yii::t('app', 'Password'), 'type' => 'password', 'required' => true)); ?>
                <span class="text-left"><?php echo $form->bsError($model, 'password'); ?></span>
            </div>
            <div class="form-group <?= $form->bsError($model, 'passwordc') ? 'has-error' : '' ?>">
                <?= $form->bsPasswordField($model, 'passwordc', array('placeholder' => Yii::t('app', 'Confirm Password'), 'type' => 'password', 'required' => true)); ?>
                <span class="text-left"><?php echo $form->bsError($model, 'passwordc'); ?></span>
            </div>

            <button type="submit" class="btn btn-primary full-width m-b">Change Password</button>

        </div>
    </div>
</div>
<?php $this->endWidget(); ?>