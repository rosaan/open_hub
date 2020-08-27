<?php
$this->pageTitle = Yii::t('app', 'Login');
$this->breadcrumbs = array(
    Yii::t('app', 'Login'),
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

            <h3>Welcome to Second Startup Hub</h3>
            <p>Sign in to start your session</p>

            <div class="form-group <?= $form->bsError($model, 'username') ? 'has-error' : '' ?>">
                <?= $form->bsEmailTextField($model, 'username', array('placeholder' => Yii::t('app', 'Username'), 'type' => 'email', 'required' => true)); ?>
                <span class="text-left"><?= $form->bsError($model, 'username'); ?></span>

            </div>
            <div class="form-group <?= $form->bsError($model, 'password') ? 'has-error' : '' ?>">
                <?= $form->bsPasswordField($model, 'password', array('placeholder' => Yii::t('app', 'Password'), 'type' => 'password', 'required' => true)); ?>
                <span class="text-left"><?= $form->bsError($model, 'password'); ?></span>
            </div>


            <button type="submit" class="btn btn-primary full-width m-b">Sign In</button>

            <div class="social-auth-links text-center mb-3">
                <p>- OR -</p>
                <a href="#" class="btn btn-block" style="background: #3b5998; color: white">
                    <i class="fa fa-facebook mr-2"></i> Sign in using Facebook
                </a>
                <a href="#" class="btn btn-block" style="background: #db3236; color: white">
                    <i class="fa fa-google mr-2"></i> Sign in using Google
                </a>
                <a href="#" class="btn btn-block" style="background: #0e76a8; color: white">
                    <i class="fa fa-linkedin mr-2"></i> Sign in using LinkedIn
                </a>
            </div>

            <a href="#"><small>Forgot password?</small></a>
            <p class="text-muted text-center"><small>Do not have an account?</small></p>
            <a class="btn btn-sm btn-white btn-block" href="<?= $this->createUrl('//auth/signup') ?>">Create an account</a>

        </div>
    </div>
</div>
<?php $this->endWidget(); ?>