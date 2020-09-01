<?php
$this->pageTitle = Yii::t('app', 'Sign-up');
$this->breadcrumbs = array(
    Yii::t('app', 'Sign-up'),
);
?>

<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => 'form-signup',
    'htmlOptions' => array(
        'class' => 'm-t',
        'role' => 'form'
    )
)); ?>

<div class="middle-box text-center animated fadeInDown" style="max-width: 480px">
    <div class="ibox">
        <div class="ibox-content">
            <h3>Welcome to Second Startup Hub</h3>
            <p>Signup to start your session</p>
            <form class="m-t" role="form" action="index.html">
                <div class="form-group <?= $form->bsError($model, 'email') ? 'has-error' : '' ?>">
                    <?= $form->bsEmailTextField($model, 'email', array('placeholder' => Yii::t('app', 'Email'), 'type' => 'email', 'required' => true)); ?>
                    <span class="text-left"><?php echo $form->bsError($model, 'email'); ?></span>
                </div>
                <div class="form-group <?= $form->bsError($model, 'firstname') ? 'has-error' : '' ?>">
                    <?= $form->bsTextField($model, 'firstname', array('placeholder' => Yii::t('app', 'Firstname'), 'type' => 'text', 'required' => true)); ?>
                    <span class="text-left"><?php echo $form->bsError($model, 'firstname'); ?></span>
                </div>
                <div class="form-group <?= $form->bsError($model, 'lastname') ? 'has-error' : '' ?>">
                    <?= $form->bsTextField($model, 'lastname', array('placeholder' => Yii::t('app', 'Lastname'), 'type' => 'text', 'required' => true)); ?>
                    <span class="text-left"><?php echo $form->bsError($model, 'lastname'); ?></span>
                </div>
                <div class="form-group <?= $form->bsError($model, 'password') ? 'has-error' : '' ?>">
                    <?= $form->bsPasswordField($model, 'password', array('placeholder' => Yii::t('app', 'Password'), 'type' => 'password', 'required' => true)); ?>
                    <span class="text-left"><?php echo $form->bsError($model, 'password'); ?></span>
                </div>
                <div class="form-group <?= $form->bsError($model, 'passwordc') ? 'has-error' : '' ?>">
                    <?= $form->bsPasswordField($model, 'passwordc', array('placeholder' => Yii::t('app', 'Confirm Password'), 'type' => 'password', 'required' => true)); ?>
                    <span class="text-left"><?php echo $form->bsError($model, 'passwordc'); ?></span>
                </div>
                <div class="text-left py-2">
                    <small>By signing up, you agree to the Terms and Conditions set out by SecondStartup, including our Cookie Use.</small>
                </div>
                <button type="submit" class="btn btn-primary full-width m-b">Signup</button>

                <div class="social-auth-links text-center mb-3">
                    <p>- OR -</p>
                    <a href="#" class="btn btn-block" style="background: #3b5998; color: white">
                        <i class="fa fa-facebook mr-2"></i> Signup using Facebook
                    </a>
                    <a href="#" class="btn btn-block" style="background: #db3236; color: white">
                        <i class="fa fa-google mr-2"></i> Signup using Google
                    </a>
                    <a href="#" class="btn btn-block" style="background: #0e76a8; color: white">
                        <i class="fa fa-linkedin mr-2"></i> Signup using LinkedIn
                    </a>
                </div>

                <p class="text-muted text-center"><small>Already have an account?</small></p>
                <a class="btn btn-sm btn-white btn-block" href="<?= $this->createUrl('//auth/login') ?>">Login</a>
            </form>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>