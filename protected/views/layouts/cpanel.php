<?php $this->beginContent('layouts.frontend'); ?>

<div class="container">
    <?php if ($this->cpanelMenuInterface === 'cpanelNavCompanyInformation') {
	?>
        <div class="col-md-3">
            <h2 style="margin: 0;" class="break-word"><?php echo HUB::cpanelNavItems($this, ($this->cpanelMenuInterface === 'cpanelNavCompanyInformation') ? 'company-information' : 'default')[0]['label'] ?></h2>
            <a href="<?php echo $this->createUrl('/organization/list', array('realm' => 'cpanel')); ?>">
                <p>Back to company list</p>
            </a>
        </div>
    <?php
} else {
		?>
        <h2><?php echo HUB::cpanelNavItems($this, ($this->cpanelMenuInterface === 'cpanelNavCompanyInformation') ? 'company-information' : 'default')[0]['label'] ?></h2>
    <?php
	} ?>
</div>

<div class="col-md-3 mb-12">
    <?php $this->renderPartial('//layouts/_cpanel/_nav', array('model' => HUB::cpanelNavItems($this, ($this->cpanelMenuInterface === 'cpanelNavCompanyInformation') ? 'company-information' : 'default')[0]['items'])); ?>
</div>

<div class="col-md-9 mb-12">
    <?php echo $content; ?>
</div>
<?php $this->endContent(); ?>
