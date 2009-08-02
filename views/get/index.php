<?php if (!defined('APPLICATION')) exit(); ?>
<div class="SubTitleWrapper">
   <div class="SubTitle">
      <h1><?php
if ($this->Addon->File == '') {
	echo 'The requested addon could not be found';
} else {
	echo 'Downloading: ' . $this->Addon->Name . ' version ' . $this->Addon->Version;
?></h1>
	</div>
</div>
<div id="Content" class="container_12">
	<div class="grid_6">
		<h2>Your download should begin shortly</h2>
		<p>If your download does not begin right away, <a href="<?php echo '/uploads/'.$this->Addon->File; ?>">click here to download now</a>.</p>
		
		<h2>Need help installing this addon?</h2>
		<p>There should be a readme file in the addon with more specific instructions on how to install it. If you are still having problems, <a href="http://vanillaforums.org/discussions">ask for help on the community forums</a>.</p>
	</div>
	<div class="grid_6">
		<h2>Note</h2>
		<p>Vanilla Forums Inc cannot be held liable for issues that arise from the download or use of these addons.</p>
		
		<h2>Now what?</h2>
		<p>Head on back to the <a href="<?php echo Url('/addon/'.$this->Addon->AddonID); ?>"><?php echo $this->Addon->Name; ?> page</a>, search for <a href="http://vanillaforums.org/addons">more add-ons</a>, or you can <a href="http://vanillaforums.org/docs">learn how to make your own</a>.</p>
	</div>
	<div class="clearfix"></div>
</div>
<?php
}