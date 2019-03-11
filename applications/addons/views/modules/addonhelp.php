<?php if (!defined('APPLICATION')) exit(); ?>

<?php
if (Gdn::session()->isValid()) {
    echo anchor('Upload New Addon', '/addon/add', 'Button BigButton');
} else {
    echo anchor('Get Vanilla Now', '/download', 'Button BigButton');
}
?>

<div class="Box BoxFilter">
    <h4>Do it yourself!</h4>
    <ul class="PanelInfo">
        <li><a class="ItemLink" href="http://docs.vanillaforums.com/developers/contributing/">How to Contribute</a></li>
        <li><a class="ItemLink" href="http://docs.vanillaforums.com/developers/plugins/quickstart">Plugin Quickstart</a></li>
        <li><a class="ItemLink" href="https://github.com/vanilla/vanilla">Vanilla On GitHub</a></li>
        <li><a class="ItemLink" href="https://github.com/vanilla/community">This Directory's Code</a></li>
    </ul>
</div>

<div class="Box">
    <h4>What is this stuff?</h4>
    <p class="PanelText userContent">Addons are custom features that you can add to your Vanilla forum. Addons are created by our community of developers and people like you!</p>
</div>

<div class="Box">
    <h4>Will it work on my Vanilla Forum?</h4>
    <div class="PanelText userContent">
        <p>These addons are for people who downloaded and set up their own Vanilla forum. Compare your downloaded version with the version requirements on the right.</p>
        <p>If your Vanilla forum is hosted at <a href="http://vanillaforums.com">VanillaForums.com</a>, this addon may already be installed there.</p>
    </div>
</div>
