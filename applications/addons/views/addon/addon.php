<?php if (!defined('APPLICATION')) exit();
include($this->FetchViewLocation('helper_functions'));
$Session = Gdn::Session();
?><div itemscope itemtype="http://schema.org/SoftwareApplication"><?php
    echo '<link itemprop="url" href="' . htmlspecialchars(Gdn::Controller()->CanonicalUrl()) . '" />';

    if ($this->DeliveryType() == DELIVERY_TYPE_ALL) {
        // echo $this->FetchView('head');
        ?>
        <h1>
            <div>
                <?php
                echo T('Found in: ');
                echo Anchor('Addons', '/addon/browse/');
                ?>
                <span>&rarr;</span> <?php
                $TypesPlural = array_flip($this->Data('_TypesPlural'));
                $TypePlural = GetValue($this->Data('AddonTypeID'), $TypesPlural, 'all');
                echo Anchor(T($TypePlural), '/addon/browse/' . strtolower($TypePlural), '', array('itemprop' => 'softwareApplicationCategory'));
                ?>
            </div>
            <span itemprop="name"><?php echo $this->Data('Name'); ?></span>
            <span itemprop="softwareVersion"><?php echo $this->Data('Version'); ?></span>
        </h1>
        <?php
        $AddonID = $this->Data('AddonID');
        $AddonVersionID = $this->Data('AddonVersionID');
        $Ver = ($this->Data('Checked') ? '' : 'v1');
        $Ver2 = ($this->Data('Checked') || $this->Data('Vanilla2') ? '' : 'v1');
        if ($Session->UserID == $this->Data('InsertUserID') || $Session->CheckPermission('Addons.Addon.Manage')) {
            echo '<div class="AddonOptions">';
            echo Anchor('Edit Details', "/addon/edit{$Ver}/$AddonID", 'Popup');
            echo '|' . Anchor('Upload New Version', "/addon/newversion{$Ver2}/$AddonID");
            echo '|' . Anchor('Upload Screen', '/addon/addpicture/' . $AddonID);
            echo '|' . Anchor('Upload Icon', '/addon/icon/' . $AddonID);
            if ($Session->CheckPermission('Addons.Addon.Manage'))
                echo '|' . Anchor('Check', '/addon/check/' . $AddonID);
            if ($Session->CheckPermission('Addons.Addon.Manage'))
                echo '|' . Anchor($this->Data('DateReviewed') == '' ? 'Approve Version' : 'Unapprove Version', '/addon/approve?addonversionid=' . $AddonVersionID, 'ApproveAddon');
            if ($Session->CheckPermission('Addons.Addon.Manage'))
                echo '|' . Anchor('Delete Addon', '/addon/delete/' . $AddonID . '?Target=/addon', 'DeleteAddon');

            $this->FireEvent('AddonOptions');

            echo '</div>';
        }
        if ($this->Data('DateReviewed') == '')
            echo '<div class="Warning"><strong>Warning!</strong> This community-contributed addon has not been tested or code-reviewed. Use at your own risk.</div>';
        else
            echo '<div class="Approved"><strong>Approved!</strong> This addon has been reviewed and approved by Vanilla Forums staff.</div>';
        ?>
        <div class="Legal">
            <div class="DownloadPanel">
                <?php
                writeDownloadBox($this);
                writeRequirementBox($this);
                writeVersionBox($this);
                writeConfidenceBox($this);

                if ($Session->IsValid()) {
                    echo Anchor('Ask a Question', 'post/discussion?AddonID=' . $AddonID, 'BigButton');
                } else {
                    echo Anchor('Sign In', '/entry/?Target=' . urlencode($this->SelfUrl), 'BigButton' . (SignInPopup() ? ' SignInPopup' : ''));
                }
                ?>
            </div>
            <?php
            $AddonType = ucfirst($this->Data('Type'));
            if ($AddonType && $AddonType != 'Core') {
                $TypeHelp = T('AddonHelpFor' . $AddonType, '');
                if ($TypeHelp)
                    echo '<div class="Help">' . $TypeHelp . '</div>';
            }

            if ($this->Data('Icon') != '') {
                echo '<img class="Icon" src="' . Gdn_Upload::Url($this->Data('Icon')) . '" itemprop="image" />';
            }

            $CurrentVersion = $this->Data('CurrentVersion');
            if ($CurrentVersion && $CurrentVersion != $this->Data('Version')) {
                echo '<p>', sprintf(T("This is not the most recent version of this plugin.", 'This is not the most recent version of this plugin. For the most recent version click <a href="%s">here</a>.'), URL('addon/' . AddonModel::Slug($this->Data, FALSE))), '</p>';
            }

            echo '<div itemprop="description">';

            echo Gdn_Format::Html($this->Data('Description'));
            if ($this->Data('Description2') && $Ver != 'v1') {
                echo '<br /><br />', Gdn_Format::Html($this->Data('Description2'));
            }

            echo '</div>';
            ?>
        </div>
            <?php
            if ($this->PictureData->NumRows() > 0) {
                ?>
            <div class="PictureBox">
            <?php
            foreach ($this->PictureData->Result() as $Picture) {
                echo '<span class="AddonPicture">';
                echo '<a rel="popable[gallery]" href="#Pic_' . $Picture->AddonPictureID . '"><img src="' . Gdn_Upload::Url(ChangeBasename($Picture->File, 'at%s')) . '" itemprop="screenshot" /></a>';

                if ($Session->UserID == $this->Data('InsertUserID') || $Session->CheckPermission('Addons.Addon.Manage')) {
                    echo '<a class="Popup DeletePicture" href="' . Url('/addon/deletepicture/' . $Picture->AddonPictureID) . '">x</a>';
                }

                echo '<div id="Pic_' . $Picture->AddonPictureID . '" style="display: none;"><img src="' . Gdn_Upload::Url(ChangeBasename($Picture->File, 'ao%s')) . '" /></div>';

                echo '</span>';
            }
            ?>
            </div>
                <?php
            }
            ?>
        <h2 class="Questions" style="position:relative;">Questions</h2>
        <?php if (is_object($this->DiscussionData) && $this->DiscussionData->NumRows() > 0) { ?>
            <ul class="DataList Discussions">
            <?php
            $this->ShowOptions = FALSE;
            include($this->FetchViewLocation('discussions', 'DiscussionsController', 'vanilla'));
            ?>
            </ul>
                <?php
            } else {
                ?>
            <div class="Empty"><?php echo T('No questions yet.'); ?></div>
            <?php
        }
    }
    ?>
</div>