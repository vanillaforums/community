<?php if (!defined('APPLICATION')) exit(); ?>
<h1>Downloading <?php echo $this->Addon->Name. ' ' .$this->Addon->Version; ?></h1>
<div class="Box DownloadInfo">
	<strong>Your download should begin shortly</strong>
	<p>If your download does not begin right away,
	<a href="<?php echo '/uploads/'.$this->Addon->File; ?>">click here to
	download now</a>.</p>
	
	<p>If you have no idea what to do with this download,
	<a href="http://vanillaforums.com">we recommend that you create a forum at
	VanillaForums.com</a>.</p>
	
	<strong>What Next?</strong>
	<p>Check out our <?php echo Anchor('guide to installing Vanilla', '/page/InstallationInstructions'); ?>
	and you'll be up and running in no time.</p>
	<p>If you're upgrading from an older installation of Vanilla, we've got
	<?php echo Anchor('instructions for that, too', '/page/UpgradeInstructions'); ?>.</p>
	<p>If you run into any issues	while getting set up,
	<?php echo Anchor('visit our support community', '/discussions'); ?> where
	our active and friendly community of users and developers volunteer their
	time to help you out.</p>
</div>