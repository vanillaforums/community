<?php if (!defined('APPLICATION')) exit(); ?>

<?php
if (Gdn::session()->isValid()) {
    echo anchor('Upload New Addon', '/addon/add', 'Button BigButton');
} else {
    echo anchor('Get Vanilla Now', '/download', 'Button BigButton');
}
?>

<div class="Box">
    <h4>Do it yourself!</h4>
    <ul>
        <?php echo wrap(anchor(t('How to Contribute'), 'http://docs.vanillaforums.com/developers/contributing/'), 'li'); ?>
        <?php echo wrap(anchor(t('Plugin Quickstart'), 'http://docs.vanillaforums.com/developers/plugins/quickstart'), 'li'); ?>
        <?php echo wrap(anchor(t('Vanilla On GitHub'), 'https://github.com/vanilla/vanilla'), 'li'); ?>
        <?php echo wrap(anchor(t('This Directory&rsquo;s Code'), 'https://github.com/vanilla/community'), 'li'); ?>
    </ul>
</div>

<div class="Box What">
    <h4>What is this stuff?</h4>
    <p>Addons are custom features that you can add to your Vanilla forum. Addons are created by our community of developers and people like you!</p>
</div>

<div class="Box Work">
    <h4>Will it work on my Vanilla Forum?</h4>
    <p>These addons are for people who downloaded and set up their own Vanilla forum. Compare your downloaded version with the version requirements on the right.</p>
    <p>If your Vanilla forum is hosted at <a href="http://vanillaforums.com">VanillaForums.com</a>, this addon may already be installed there.</p>
</div>
