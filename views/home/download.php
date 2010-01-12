<?php if (!defined('APPLICATION')) exit(); ?>
<div class="DownloadOptions">
	<ul class="SideMenu">
		<li class="Highlight"><?php echo Anchor('Stable Download', '/download'); ?>→</li>
		<li><?php echo Anchor('Beta Releases', '/page/BetaReleases'); ?></li>
		<li><?php echo Anchor('Release Archive', '/page/ReleaseArchive'); ?></li>
		<li><?php echo Anchor('GitHub Access', '/page/GitHub'); ?></li>
	</ul>
</div>
<div class="DownloadInfo">
	<p><strong>The latest stable release of Vanilla (version 1.1.9) is available in ZIP format below. If you have no idea what
	to do with this download, <a href="http://vanillaforums.com">we recommend getting an account at VanillaForums.com</a>
	and letting us take care of that stuff for you.</strong></p>
	
	<p class="ChunkyButton"><?php echo Anchor('Download Vanilla 1.1.9', '/get/Vanilla'); ?></p>
	
	<h4>Next?</h4>
	<p>Check out our <?php echo Anchor('guide to installing Vanilla', '/page/InstallationInstructions'); ?>
	and you'll be up and running in no time. If you're upgrading from an older installation of Vanilla, we've got
	<?php echo Anchor('instructions for that, too', '/page/UpgradeInstructions'); ?>. If you run into any issues
	while getting set up, <?php echo Anchor('visit our forums', '/discussions'); ?> where our active and friendly
	community of users and developers volunteer their time to help you out.</p>
</div>