<?php if (!defined('APPLICATION')) exit();
$Session = Gdn::session();
$VanillaVersion = $this->data('Vanilla2') == '1' ? '2' : '1';

?><div itemscope itemtype="http://schema.org/SoftwareApplication"><?php

echo '<link itemprop="url" href="'.htmlspecialchars(Gdn::controller()->canonicalUrl()).'" />';

if ($this->deliveryType() == DELIVERY_TYPE_ALL) {
    ?>
    <h1>
        <div>
            <?php echo t('Found in: ');
            echo anchor('Addons', '/addon/browse/');
            ?>
            <span>&rarr;</span> <?php
                $TypesPlural = array_flip($this->data('_TypesPlural'));
                $TypePlural = val($this->data('AddonTypeID'), $TypesPlural, 'all');
                echo anchor(t($TypePlural), '/addon/browse/'.strtolower($TypePlural), '', array('itemprop' => 'softwareApplicationCategory'));
            ?>
        </div>
        <span itemprop="name"><?php echo $this->data('Name'); ?></span>
        <span itemprop="softwareVersion"><?php echo $this->data('Version'); ?></span>
    </h1>
    <?php
    $AddonID = $this->data('AddonID');
    $AddonVersionID = $this->data('AddonVersionID');
    $Ver = ($this->data('Checked') ? '' : 'v1');
    $Ver2 = ($this->data('Checked') || $this->data('Vanilla2') ? '' : 'v1');
    $Author = $this->data('Official') ? t('Vanilla Staff') : userAnchor($this->Data, null, array('Px' => 'Insert', 'Rel' => 'author'));
    $OfficialInverse = $this->data('Official') ? 'Unofficial' : 'Official';

    if ($Session->UserID == $this->data('InsertUserID') || checkPermission('Addons.Addon.Manage')) {
        echo '<div class="AddonOptions">';
        echo anchor('Edit Details', "/addon/edit{$Ver}/$AddonID", 'Popup');
        echo '|'.anchor('Upload New Version', "/addon/newversion{$Ver2}/$AddonID");
        echo '|'.anchor('Upload Screenshot', '/addon/addpicture/'.$AddonID);
        echo '|'.anchor('Upload Icon', '/addon/icon/'.$AddonID);
        echo '|'.anchor('Mark as '.$OfficialInverse, '/addon/official/'.$AddonID.'?TransientKey='.urlencode(Gdn::session()->transientKey()));

        if (checkPermission('Addons.Addon.Manage')) {
            echo '|'.anchor('DELETE ADDON', '/addon/delete/'.$AddonID.'?TransientKey='.urlencode(Gdn::session()->transientKey()).'&Target=/addon', 'Popup DeleteAddon Alert');
        }
        $this->fireEvent('AddonOptions');

        echo '</div>';
    }

    ?>
    <?php if ($this->data('Official')) : ?>
    <div class="Approved"><strong>Official!</strong> This product is maintained by the Vanilla Forums staff and core team.</div>
    <?php endif; ?>
    <div class="Legal">
        <div class="DownloadPanel">
            <div class="Box DownloadBox">
                <p><?php echo anchor('Download Now', '/get/'.($this->data('Slug') ? urlencode($this->data('Slug')) : $AddonID), 'Button BigButton', array('itemprop' => 'downloadURL')); ?></p>
                <dl>
                    <dt>Author</dt>
                    <dd><?php echo $Author; ?></dd>

                    <dt>Version</dt>
                    <dd><?php echo $this->data('Version');
                        $CurrentVersion = $this->data('CurrentVersion');
                        if ($CurrentVersion && $CurrentVersion != $this->data('Version')) {
                            echo ' ', anchor('('.t('Current').')', '/addon/'.AddonModel::slug($this->Data, false));
                        }
                        echo '&#160;';
                        ?></dd>

                    <dt>Updated</dt>
                    <dd itemprop="datePublished"><?php echo Gdn_Format::date($this->data('DateUploaded'), 'html'); ?></dd>

                    <dt>Downloads</dt>
                    <dd><meta itemprop="interactionCount" content=”UserDownloads:<?php echo $this->data('CountDownloads'); ?>" /><?php echo number_format($this->data('CountDownloads')); ?></dd>

                    <?php
                    if ($this->data('FileSize')) {
                        echo wrap(t('File Size'), 'dt');
                        echo wrap('<meta itemprop="fileSize" content="'.$this->data('FileSize').'"/>'.Gdn_Upload::FormatFileSize($this->data('FileSize')), 'dd');
                    }

                    if ($this->data('License')) {
                        echo wrap(t('License'), 'dt');
                        echo wrap(htmlspecialchars($this->data('License')), 'dd');
                    }

                    $this->fireEvent('AddonProperties');
                    ?>
                </dl>
            </div>
            <?php if (is_array($this->data('Requirements')) && count($this->data('Requirements'))) : ?>
            <div class="Box RequirementBox">
                <h3><?php echo t('Requirements'); ?></h3>
                <div>
                <?php
                $Reqs = '';
                foreach ($this->data('Requirements') as $ReqType => $ReqItems) {
                    if (!is_array($ReqItems) || count($ReqItems) == 0) {
                        continue;
                    }
                    $Reqs .= '<dt>'.t($ReqType).'</dt>';
                    $Reqs .= '<dd>'.htmlspecialchars(implodeAssoc(' ', ', ', $ReqItems)).'</dd>';
                }
                if ($Reqs) {
                    echo "<dl>$Reqs</dl>";
                }
                ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="Box AddonBox VersionsBox">
                <h3><?php echo t('Version History'); ?></h3>
                <table class="VersionsTable">
                <?php
                $Versions = (array)$this->data('Releases');
                if (count($Versions) > 0) {
                    $i = 1;
                    foreach ($Versions as $Version) {
                        if ($i > 5) {
                            break;
                        }
                        $i++;
                        $Url = url('/addon/'.AddonModel::slug($this->Data, false).'-'.$Version['Version']);
                        $deleteOption = '';
                        if (checkPermission('Addons.Addon.Manage')) {
                            $deleteOption = ' '.anchor('x', '/addon/deleteversion/'.$Version['AddonVersionID'], 'Popup Alert DeleteVersion');
                        }
                        echo '<tr>'.
                            '<td>'.anchor(htmlspecialchars($Version['Version']), $Url).'</td>'.
                            '<td class="DateColumn">'.anchor(htmlspecialchars(Gdn_Format::date($Version['DateInserted'])), $Url).$deleteOption.'</td>'.
                        '</tr>';
                    }
                } else {
                    echo '<tr><th colspan="2">No stable versions found!</th></tr>';
                }
                ?>
                <?php if (checkPermission('Addons.Addon.Manage')) : ?>
                <tfoot>
                    <tr><th colspan="2"><?php echo anchor('View details', '/addon/check/'.$this->data('AddonID')); ?></th></tr>
                </tfoot>
                <?php endif; ?>

                </table>
            </div>

            <?php
            $Versions = (array)$this->data('Prereleases');
            if (count($Versions) > 0) : ?>
            <div class="Box AddonBox VersionsBox">
                <h3><?php echo t('Prerelease (unstable)'); ?></h3>
                <table class="VersionsTable">
                <?php
                $i = 1;
                foreach ($Versions as $Version) {
                    if ($i > 3) {
                        break;
                    }
                    $i++;
                    $Url = url('/addon/'.AddonModel::slug($this->Data, false).'-'.$Version['Version']);
                    $deleteOption = '';
                    if (checkPermission('Addons.Addon.Manage')) {
                        $deleteOption = ' '.anchor('x', '/addon/deleteversion/'.$Version['AddonVersionID'], 'Popup Alert DeleteVersion');
                    }
                    echo '<tr>'.
                        '<td>'.anchor(htmlspecialchars($Version['Version']), $Url).'</td>'.
                        '<td class="DateColumn">'.anchor(htmlspecialchars(Gdn_Format::date($Version['DateInserted'])), $Url).$deleteOption.'</td>'.
                    '</tr>';
                }
                ?>
                </table>
            </div>
            <?php endif; ?>

            <?php
            if ($Session->isValid()) {
                echo anchor('Ask a Question', 'post/discussion?AddonID='.$AddonID, 'Button BigButton');
            }
            ?>
        </div>
    <?php

    $AddonType = ucfirst($this->data('Type'));
    if ($AddonType && $AddonType != 'Core') {
        $TypeHelp = t('AddonHelpFor'.$AddonType, '');
        if ($TypeHelp)
            echo '<div class="Help">'.$TypeHelp.'</div>';
    }

    if ($this->data('Icon') != '') {
        echo '<img class="Icon" src="'.Gdn_Upload::url($this->data('Icon')).'" itemprop="image" />';
    }

    $CurrentVersion = $this->data('CurrentVersion');
    if ($CurrentVersion && $CurrentVersion != $this->data('Version')) {
        echo '<p>', sprintf(t("This is not the most recent version of this plugin.", 'This is not the most recent version of this plugin. For the most recent version click <a href="%s">here</a>.'), URL('addon/'.AddonModel::Slug($this->Data, FALSE))), '</p>';
    }

    echo '<div itemprop="description">';
    echo Gdn_Format::html($this->data('Description'));
    if ($this->data('Description2') && $Ver != 'v1') {
        echo '<br /><br />', Gdn_Format::markdown($this->data('Description2'));
    }
    echo '</div>';

    ?>
    </div>
    <?php
    if ($this->PictureData->numRows() > 0) {
        ?>
        <div class="PictureBox">
            <?php
            foreach ($this->PictureData->result() as $Picture) {
                echo '<span class="AddonPicture">';
                echo '<a rel="popable[gallery]" href="#Pic_'.$Picture->AddonPictureID.'"><img src="'.Gdn_Upload::url(ChangeBasename($Picture->File, 'at%s')).'" itemprop="screenshot" /></a>';

                if ($Session->UserID == $this->data('InsertUserID') || checkPermission('Addons.Addon.Manage')) {
                    echo '<a class="Popup DeletePicture" href="'.Url('/addon/deletepicture/'.$Picture->AddonPictureID).'">x</a>';
                }

                echo '<div id="Pic_'.$Picture->AddonPictureID.'" style="display: none;"><img src="'.Gdn_Upload::url(ChangeBasename($Picture->File, 'ao%s')).'" /></div>';

                echo '</span>';
            }
            ?>
        </div>
        <?php
    }
    ?>
    <h2 class="Questions" style="position:relative;">Questions</h2>
    <?php if (is_object($this->DiscussionData) && $this->DiscussionData->numRows() > 0) { ?>
    <ul class="DataList Discussions">
        <?php
        $this->ShowOptions = false;
        include($this->fetchViewLocation('discussions', 'DiscussionsController', 'vanilla'));
        ?>
    </ul>
    <?php
    } else {
        ?>
        <div class="Empty"><?php echo t('No questions yet.'); ?></div>
        <?php
    }
}
?>
</div>