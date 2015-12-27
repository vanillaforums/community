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
    if ($Session->UserID == $this->data('InsertUserID') || $Session->checkPermission('Addons.Addon.Manage')) {
        echo '<div class="AddonOptions">';
        echo anchor('Edit Details', "/addon/edit{$Ver}/$AddonID", 'Popup');
        echo '|'.anchor('Upload New Version', "/addon/newversion{$Ver2}/$AddonID");
        echo '|'.anchor('Upload Screenshot', '/addon/addpicture/'.$AddonID);
        echo '|'.anchor('Upload Icon', '/addon/icon/'.$AddonID);

        if ($Session->checkPermission('Addons.Addon.Manage')) {
            echo '|'.anchor('DELETE ADDON', '/addon/delete/'.$AddonID.'?TransientKey='.urlencode(Gdn::session()->transientKey()).'&Target=/addon', 'DeleteAddon Alert');
        }
        $this->FireEvent('AddonOptions');

        echo '</div>';
    }

    ?>
    <div class="Legal">
        <div class="DownloadPanel">
            <div class="Box DownloadBox">
                <p><?php echo anchor('Download Now', '/get/'.($this->data('Slug') ? urlencode($this->data('Slug')) : $AddonID), 'Button BigButton', array('itemprop' => 'downloadURL')); ?></p>
                <dl>
                    <dt>Author</dt>
                    <dd><?php echo Useranchor($this->Data, NULL, array('Px' => 'Insert', 'Rel' => 'author')); ?></dd>

                    <dt>Version</dt>
                    <dd><?php echo $this->data('Version');
                        $CurrentVersion = $this->data('CurrentVersion');
                        if ($CurrentVersion && $CurrentVersion != $this->data('Version')) {
                            echo ' ', anchor('('.t('Current').')', '/addon/'.AddonModel::slug($this->Data, FALSE));
                        }
                        echo '&#160;';
                        ?></dd>

                    <dt>Released</dt>
                    <dd itemprop="datePublished"><?php echo Gdn_Format::Date($this->data('DateUploaded'), 'html'); ?></dd>

                    <dt>Downloads</dt>
                    <dd><meta itemprop="interactionCount" content=”UserDownloads:<?php echo $this->data('CountDownloads'); ?>" /><?php echo number_format($this->data('CountDownloads')); ?></dd>

                    <?php
                    if ($this->data('FileSize')) {
                        echo '<dt>File Size</dt><dd>'.'<meta itemprop="fileSize" content="'.$this->data('FileSize').'"/>'.Gdn_Upload::FormatFileSize($this->data('FileSize')).'</dd>';
                    }

                    $this->fireEvent('AddonProperties');
                    ?>
                </dl>
            </div>
            <div class="Box RequirementBox">
                <h3><?php echo t('Requirements'); ?></h3>
                <div>
                <?php
                if (is_array($this->data('Requirements'))) {
                    $Reqs = '';
                    foreach ($this->data('Requirements') as $ReqType => $ReqItems) {
                        if (!is_array($ReqItems) || count($ReqItems) == 0) {
                            continue;
                        }
                        $Reqs .= '<dt>'.t($ReqType).'</dt>';
                        $Reqs .= '<dd>'.htmlspecialchars(ImplodeAssoc(' ', ', ', $ReqItems)).'</dd>';
                    }
                    if ($Reqs) {
                        echo "<dl>$Reqs</dl>";
                    }
                } else {
                    $OtherRequirements = Gdn_Format::html($this->data('Requirements'));
                    if ($OtherRequirements) {
                        echo $OtherRequirements;
                    }
                }
                ?>
                </div>
            </div>
            <?php
            $Versions = (array)$this->data('Versions');
            if (count($Versions) > 0):
            ?>
            <div class="Box AddonBox VersionsBox">
                <h3><?php echo t('Version History'); ?></h3>
                <table class="VersionsTable">
                <?php
                $i = 1;
                foreach ($Versions as $Version) {
                    if ($i > 5) {
                        break;
                    }
                    $i++;
                    $Url = url('/addon/'.AddonModel::slug($this->Data, FALSE).'-'.$Version['Version']);

                    echo '<tr>'.
                        '<td>'.anchor(htmlspecialchars($Version['Version']), $Url).'</td>'.
                        '<td class="DateColumn">'.anchor(htmlspecialchars(Gdn_Format::date($Version['DateInserted'])), $Url).'</td>'.
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
        echo '<br /><br />', Gdn_Format::html($this->data('Description2'));
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

                if ($Session->UserID == $this->data('InsertUserID') || $Session->checkPermission('Addons.Addon.Manage')) {
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