<?php if (!defined('APPLICATION')) exit(); ?>

<div class="Box">
    <h4>Make Your Own Addons!</h4>
    <ul>
    <?php
        echo '<li>'.anchor('Quick-Start Guide', 'http://docs.vanillaforums.com/developers/plugins/quickstart/').'</li>';
        if (Gdn::session()->isValid()) {
            echo '<li>'.anchor('Upload a New Addon', '/addon/add').'</li>';
        } else {
            echo '<li>'.anchor('Sign In', Gdn::authenticator()->signInUrl('/addons'), signInPopup() ? 'SignInPopup' : '').'</li>';
        }
    ?>
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

<div class="Box DownloadPanelBox">
    <h4>Don't have Vanilla yet?</h4>
    <?php echo anchor('Get Vanilla Now', '/download', 'Button BigButton'); ?>
</div>
