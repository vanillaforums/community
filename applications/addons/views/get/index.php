<?php if (!defined('APPLICATION')) exit(); ?>
<h1><?php
if ($this->Addon['File'] == '') {
    echo 'The requested addon could not be found';
} else {
    echo 'Downloading: ' . htmlspecialchars($this->Addon['Name']) . ' version ' . htmlspecialchars($this->Addon['Version']);
?></h1>
<div class="Box DownloadInfo">
    <strong>Your download should begin shortly</strong>
    <p>If your download does not begin right away, <a href="<?php echo Gdn_Upload::url($this->Addon['File']); ?>">click here to download now</a>.</p>

    <strong>Need help installing this addon?</strong>
    <p>There should be a readme file in the addon with more specific instructions on how to install it. If you are still having problems, <a href="//vanillaforums.org/discussions">ask for help on the community forums</a>.</p>

    <strong>Note</strong>
    <p>Vanilla Forums Inc cannot be held liable for issues that arise from the download or use of these addons.</p>

    <strong>Now what?</strong>
    <p>Head on back to the <a href="<?php echo url('/addon/'.$this->Addon['AddonID']); ?>"><?php echo htmlspecialchars($this->Addon['Name']); ?> page</a>, search for <a href="//vanillaforums.org/addons">more add-ons</a>, or you can <a href="//vanillaforums.org/docs">learn how to make your own</a>.</p>
</div>
<?php
}
