<?php if (!defined('APPLICATION')) exit();
$session = Gdn::session();

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
                $typesPlural = array_flip($this->data('_TypesPlural'));
                $typePlural = val($this->data('AddonTypeID'), $typesPlural, 'all');
                echo anchor(t($typePlural), '/addon/browse/'.strtolower($typePlural), '', array('itemprop' => 'softwareApplicationCategory'));
            ?>
        </div>
        <span itemprop="name"><?php echo htmlspecialchars($this->data('Name')); ?></span>
        <span itemprop="softwareVersion"><?php echo htmlspecialchars($this->data('Version')); ?></span>
    </h1>
    <?php
    $addonID = $this->data('AddonID');
    $ver = ($this->data('Checked') ? '' : 'v1');
    $ver2 = ($this->data('Checked') || $this->data('Vanilla2') ? '' : 'v1');
    $escapedAuthor = $this->data('Official') ? t('Vanilla Staff') : userAnchor($this->Data, null, array('Px' => 'Insert', 'Rel' => 'author'));
    $manager = checkPermission('Addons.Addon.Manage');
    if ($session->UserID == $this->data('InsertUserID') || $manager) {
        echo '<div class="AddonOptions">';
        echo anchor('Edit Details', "/addon/edit{$ver}/$addonID", 'Popup');
        echo '|'.anchor('Upload New Version', "/addon/newversion{$ver2}/$addonID");
        echo '|'.anchor('Upload Screenshot', '/addon/addpicture/'.$addonID);
        echo '|'.anchor('Upload Icon', '/addon/icon/'.$addonID);

        if ($manager) {
            $officialInverse = $this->data('Official') ? 'Unofficial' : 'Official';
            echo '|'.anchor('Mark as '.$officialInverse, '/addon/official/'.$addonID.'?TransientKey='.urlencode(Gdn::session()->transientKey()));
            echo '|'.anchor('DELETE ADDON', '/addon/delete/'.$addonID.'?TransientKey='.urlencode(Gdn::session()->transientKey()).'&Target=/addon', 'Popup DeleteAddon Alert');
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
                <p><?php echo anchor('Download Now', '/get/'.($this->data('Slug') ? urlencode($this->data('Slug')) : $addonID), 'Button BigButton', array('itemprop' => 'downloadURL')); ?></p>
                <dl>
                    <?php
                    // Special Locale-only info.
                    if ($this->data('Type') == 'Locale') {
                        if ($this->data('EnName')) {
                            echo wrap(t('Name (en)'), 'dt');
                            echo wrap(htmlspecialchars($this->data('EnName')), 'dd');
                        }
                        if ($this->data('PercentComplete')) {
                            echo wrap(t('Translated'), 'dt');
                            echo wrap(htmlspecialchars($this->data('PercentComplete').'%'), 'dd');
                        }
                    }
                    ?>

                    <dt>Author</dt>
                    <dd><?php echo $escapedAuthor; ?></dd>

                    <dt>Version</dt>
                    <dd><?php echo htmlspecialchars($this->data('Version'));
                        $currentVersion = $this->data('CurrentVersion');
                        if ($currentVersion && $currentVersion != $this->data('Version')) {
                            echo ' ', anchor('('.t('Current').')', '/addon/'.AddonModel::slug($this->Data, false));
                        }
                        echo '&#160;';
                        ?></dd>

                    <dt>Updated</dt>
                    <dd itemprop="datePublished"><?php echo Gdn_Format::date($this->data('DateUploaded'), 'html'); ?></dd>

                    <dt>Downloads</dt>
                    <dd><meta itemprop="interactionCount" content="UserDownloads:<?php echo htmlspecialchars($this->data('CountDownloads')); ?>" /><?php echo number_format($this->data('CountDownloads')); ?></dd>

                    <?php
                    if ($this->data('FileSize')) {
                        echo wrap(t('File Size'), 'dt');
                        echo wrap('<meta itemprop="fileSize" content="'.htmlspecialchars($this->data('FileSize')).'"/>'.Gdn_Upload::FormatFileSize($this->data('FileSize')), 'dd');
                    }

                    if ($this->data('License')) {
                        echo wrap(t('License'), 'dt');
                        echo wrap(htmlspecialchars($this->data('License')), 'dd');
                    }

                    if ($this->data('GitHub')) {
                        echo wrap(t('GitHub'), 'dt');
                        $github = stringBeginsWith($this->data('GitHub'), 'https://github.com/', false, true);
                        echo wrap(anchor(htmlspecialchars($github), 'https://github.com/'.$github), 'dd');
                    }

                    $this->fireEvent('AddonProperties');
                    ?>
                </dl>
            </div>

            <?php
            $addonType = strtolower($this->data('Type'));
            if ($addonType && !in_array($addonType, ['core', 'locale'])) : ?>
            <div class="Box AddonBox ConfidenceBox">
                <?php
                $addonVersionID = $this->Data('AddonVersionID');
                $confidence = $this->ConfidenceModel->getID($addonVersionID, false, DATASET_TYPE_OBJECT);
                $coreVersion = $this->ConfidenceModel->getCoreVersion();

                echo wrap(sprintf(t('Vanilla %s Compatibility'), $coreVersion->Version), 'h3');

                if (!$confidence) {
                    echo wrap(sprite('Bandaid', 'BigSprite', 'Unsure') . T('The community has said nothing.'), 'p');
                }
                else {
                    $percentWorking = ($confidence->TotalWeight / $confidence->TotalVotes) * 100;
                    $title = sprintf(t('%.1f%% of %d users report it as working'), $percentWorking, $confidence->TotalVotes);
                    if ($percentWorking >= 60) {
                        echo wrap(sprite('Heart', 'BigSprite', 'Working') . T('The community says this works.'), 'p', ['title' => $title]);
                    }
                    else if ($percentWorking <= 40) {
                        echo wrap(sprite('Crossbones', 'BigSprite', 'Broken') . T('The community says this is broken.'), 'p', ['title' => $title]);
                    }
                    else {
                        echo wrap(sprite('Warning', 'BigSprite', 'Unsure') . T('The community is split.'), 'p', ['title' => $title]);
                    }
                }

                if ($session->isValid()) {
                    $data = $this->data('UserConfidenceRecord', false);

                    $worksClass = 'WorksButton Button Hijack';
                    $brokenClass = 'BrokenButton Button Hijack';

                    if ($data) {
                        $worksClass .= (($data->Weight > 0) ? ' Active' : ' Disabled');
                        $brokenClass .= (($data->Weight <= 0) ? ' Active' : ' Disabled');
                    }

                    echo '<div>';
                    echo wrap(t('What do you think?'), 'h4');
                    echo anchor(sprite('Check', 'Sprite', 'It works!') . 'It works!', 'addon/works/' . $addonVersionID . '/' . $coreVersion->AddonVersionID, ['class' => $worksClass]);
                    echo anchor(sprite('Cross', 'Sprite', 'It\'s broken!') . 'It\'s broken!', 'addon/broken/' . $addonVersionID . '/' . $coreVersion->AddonVersionID, ['class' => $brokenClass]);
                    echo '</div>';
                }
                ?>
            </div>
            <?php endif; ?>

            <?php if (is_array($this->data('Requirements')) && count($this->data('Requirements'))) : ?>
            <div class="Box RequirementBox">
                <h3><?php echo t('Requirements'); ?></h3>
                <div>
                <?php
                $reqs = '';
                foreach ($this->data('Requirements') as $reqType => $reqItems) {
                    if (!is_array($reqItems) || count($reqItems) == 0) {
                        continue;
                    }
                    $reqs .= '<dt>'.t($reqType).'</dt>';
                    $reqs .= '<dd>'.htmlspecialchars(implodeAssoc(' ', ', ', $reqItems)).'</dd>';
                }
                if ($reqs) {
                    echo "<dl>$reqs</dl>";
                }
                ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="Box AddonBox VersionsBox">
                <h3><?php echo t('Version History'); ?></h3>
                <table class="VersionsTable">
                <?php
                $versions = (array)$this->data('Releases');
                if (count($versions) > 0) {
                    $i = 1;
                    foreach ($versions as $version) {
                        if ($i > 5) {
                            break;
                        }
                        $i++;
                        $url = url('/addon/'.AddonModel::slug($this->Data, false).'-'.$version['Version']);
                        $deleteOption = '';
                        if (checkPermission('Addons.Addon.Manage')) {
                            $deleteOption = ' '.anchor('x', '/addon/deleteversion/'.$version['AddonVersionID'], 'Popup Alert DeleteVersion');
                        }
                        echo '<tr>'.
                            '<td>'.anchor(htmlspecialchars($version['Version']), $url).'</td>'.
                            '<td class="DateColumn">'.anchor(htmlspecialchars(Gdn_Format::date($version['DateInserted'])), $url).$deleteOption.'</td>'.
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
            $versions = (array)$this->data('Prereleases');
            if (count($versions) > 0) : ?>
            <div class="Box AddonBox VersionsBox">
                <h3><?php echo t('Prerelease (unstable)'); ?></h3>
                <table class="VersionsTable">
                <?php
                $i = 1;
                foreach ($versions as $version) {
                    if ($i > 3) {
                        break;
                    }
                    $i++;
                    $url = url('/addon/'.AddonModel::slug($this->Data, false).'-'.$version['Version']);
                    $deleteOption = '';
                    if (checkPermission('Addons.Addon.Manage')) {
                        $deleteOption = ' '.anchor('x', '/addon/deleteversion/'.$version['AddonVersionID'], 'Popup Alert DeleteVersion');
                    }
                    echo '<tr>'.
                        '<td>'.anchor(htmlspecialchars($version['Version']), $url).'</td>'.
                        '<td class="DateColumn">'.anchor(htmlspecialchars(Gdn_Format::date($version['DateInserted'])), $url).$deleteOption.'</td>'.
                    '</tr>';
                }
                ?>
                </table>
            </div>
            <?php endif;

            if ($session->isValid()) {
                echo anchor('Ask a Question', 'post/discussion?AddonID='.$addonID, 'Button BigButton');
            }
            ?>
        </div>
    <?php

    if ($addonType && $addonType != 'Core') {
        $typeHelp = t('AddonHelpFor'.$addonType, '');
        if ($typeHelp) {
            echo '<div class="Help">'.$typeHelp.'</div>';
        }
    }

    if ($this->data('Icon') != '') {
        echo '<img class="Icon" src="'.Gdn_Upload::url($this->data('Icon')).'" itemprop="image" />';
    }

    $currentVersion = $this->data('CurrentVersion');
    if ($currentVersion && $currentVersion != $this->data('Version')) {
        echo '<p>', sprintf(t("This is not the most recent version of this plugin.", 'This is not the most recent version of this plugin. For the most recent version click <a href="%s">here</a>.'), URL('addon/'.AddonModel::Slug($this->Data, false))), '</p>';
    }

    echo '<div itemprop="description">';
    echo Gdn_Format::html($this->data('Description'));
    if ($this->data('Description2') && $ver != 'v1') {
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
            foreach ($this->PictureData->result() as $picture) {
                echo '<span class="AddonPicture">';
                echo '<a rel="popable[gallery]" href="#Pic_'.$picture->AddonPictureID.'"><img src="'.Gdn_Upload::url(ChangeBasename($picture->File, 'at%s')).'" itemprop="screenshot" /></a>';

                if ($session->UserID == $this->data('InsertUserID') || checkPermission('Addons.Addon.Manage')) {
                    echo '<a class="Popup DeletePicture" href="'.Url('/addon/deletepicture/'.$picture->AddonPictureID).'">x</a>';
                }

                echo '<div id="Pic_'.$picture->AddonPictureID.'" style="display: none;"><img src="'.Gdn_Upload::url(ChangeBasename($picture->File, 'ao%s')).'" /></div>';

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
