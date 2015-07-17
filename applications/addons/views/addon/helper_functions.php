<?php if (!defined('APPLICATION')) exit();

function WriteAddon($Addon, $Alt) {
    $Url = '/addon/'.AddonModel::Slug($Addon, FALSE);
    ?>
    <li class="Item AddonRow<?php echo $Alt; ?>">
        <?php
        if ($Addon->Icon != '') {
            echo '<a class="Icon" href="'.Url($Url).'"><div class="IconWrap"><img src="'.Gdn_Upload::Url($Addon->Icon).'" /></div></a>';
        }
        ?>
        <div class="ItemContent">
            <?php
            echo Anchor($Addon->Name, $Url, 'Title');

            echo '<div class="Description">', Anchor(SliceString(Gdn_Format::Text($Addon->Description), 300), $Url), '</div>';
            ?>
            <div class="Meta">
                <span class="<?php echo $Addon->Vanilla2 == '1' ? 'Vanilla2' : 'Vanilla1'; ?>"><?php
                    echo $Addon->Vanilla2 == '1' ? 'Vanilla 2' : 'Vanilla 1'; ?></span>
                <?php
                if (Gdn::Session()->CheckPermission('Addons.Addon.Manage')) {
                    if ($Addon->Checked) {
                        echo '<span class="Approved">Checked</span>';
                    } elseif ($Addon->Vanilla2) {
                        echo Anchor('<span class="Closed">Check</span>', Url('/addon/check/'.$Addon->AddonID));
                    }
                }
                if ($Addon->DateReviewed != '')
                    echo '<span class="Approved">Approved</span>';
                ?>
                <span class="Type">
                    Type
                    <span><?php echo $Addon->Type; ?></span>
                </span>
                <span class="Version">
                    Version
                    <span><?php echo $Addon->Version; ?></span>
                </span>
                <span class="Author">
                    Author
                    <span><?php echo $Addon->InsertName; ?></span>
                </span>
                <span class="Downloads">
                    Downloads
                    <span><?php echo number_format($Addon->CountDownloads); ?></span>
                </span>
                <span class="Updated">
                    Updated
                    <span><?php echo Gdn_Format::Date($Addon->DateUpdated); ?></span>
                </span>
            </div>
        </div>
    </li>
<?php
}

function writeDownloadBox($sender) {
    ?>
     <div class="Box DownloadBox">
     <p><?php echo Anchor('Download Now', '/get/' . ($sender->Data('Slug') ? urlencode($sender->Data('Slug')) : $AddonID), 'Button BigButton', array('itemprop' => 'downloadURL')); ?></p>
     <dl>
         <dt>Author</dt>
         <dd><?php echo UserAnchor($sender->Data, NULL, array('Px' => 'Insert', 'Rel' => 'author')); ?></dd>
         <dt>Version</dt>
         <dd><?php
    echo $sender->Data('Version');

    $CurrentVersion = $sender->Data('CurrentVersion');
    if ($CurrentVersion && $CurrentVersion != $sender->Data('Version')) {
        echo ' ', Anchor('(' . T('Current') . ')', '/addon/' . AddonModel::Slug($sender->Data, FALSE));
    }
    echo '&#160;';
    ?></dd>
         <dt>Released</dt>
         <dd itemprop="datePublished"><?php echo Gdn_Format::Date($sender->Data('DateUploaded'), 'html'); ?></dd>
         <dt>Downloads</dt>
         <dd><meta itemprop="interactionCount" content="UserDownloads: <?php echo $sender->Data('CountDownloads'); ?>" /><?php echo number_format($sender->Data('CountDownloads')); ?></dd>
    <?php
    if ($sender->Data('FileSize'))
        echo '<dt>File Size</dt><dd>' . '<meta itemprop="fileSize" content="' . $sender->Data('FileSize') . '"/>' . Gdn_Upload::FormatFileSize($sender->Data('FileSize')) . '</dd>';
    if (Gdn::Session()->CheckPermission('Addons.Addon.Manage')) {
        echo '<dt>Checked</dt><dd>' . ($sender->Data('Checked') ? 'Yes' : 'No') . '</dd>';
    }
    $sender->FireEvent('AddonProperties');
    ?>
     </dl>
 </div>
    <?php
}

function writeRequirementBox($sender) {
    $VanillaVersion = $sender->Data('Vanilla2') == '1' ? '2' : '1';
       ?>
        <div class="Box RequirementBox">
                <h3><?php echo T('Requirements'); ?></h3>
                <div>
    				<dl>
    					<dt>Vanilla</dt>
    					<dd><span class="Vanilla<?php echo $VanillaVersion; ?>">Vanilla <?php echo $VanillaVersion; ?></span></dd>
    				</dl>
       <?php
       if (!$sender->Data('Checked')) {
           $OtherRequirements = Gdn_Format::Display($sender->Data('Requirements'));
           if ($OtherRequirements) {
               ?>
                              <p>Other Requirements:</p>
               <?php
               echo $OtherRequirements;
           }
       } else {
           if (is_array($sender->Data('Requirements'))) {
               $Reqs = '';
               foreach ($sender->Data('Requirements') as $ReqType => $ReqItems) {
                   if (!is_array($ReqItems) || count($ReqItems) == 0)
                       continue;
                   $Reqs .= '<dt>' . T($ReqType) . '</dt>';
                   $Reqs .= '<dd>' . htmlspecialchars(ImplodeAssoc(' ', ', ', $ReqItems)) . '</dd>';
               }
               if ($Reqs)
                   echo "<dl>$Reqs</dl>";
           } else {
               $OtherRequirements = Gdn_Format::Html($sender->Data('Requirements'));
               if ($OtherRequirements) {
                   echo $OtherRequirements;
               }
           }
       }
       ?>
                </div>
    		</div>
    <?php
}

function writeVersionBox($sender) {
    $Versions = (array) $sender->Data('Versions');
    if (count($Versions) > 0):
        ?>
                 <div class="Box AddonBox VersionsBox">
                    <h3><?php echo T('Latest Versions'); ?></h3>
                    <table class="VersionsTable">
                       <tr>
                          <th><?php echo T('Version'); ?></th>
                          <th class="DateColumn"><?php echo T('Released'); ?></th>
                       </tr>
        <?php
        $i = 1;
        foreach ($Versions as $Version) {
            if ($i > 5)
                break;
            $i++;

            $Url = Url('/addon/' . AddonModel::Slug($sender->Data, FALSE) . '-' . $Version['Version']);

            echo '<tr>' .
            '<td>' . Anchor(htmlspecialchars($Version['Version']), $Url) . '</td>' .
            '<td class="DateColumn">' . Anchor(htmlspecialchars(Gdn_Format::Date($Version['DateInserted'])), $Url) . '</td>' .
            '</tr>';
        }
        ?>
                    </table>
                 </div>
    <?php
    endif;
}

function writeConfidenceBox($sender) {
    $confidence = $sender->ConfidenceModel->getID($sender->Data('Versions')[0]['AddonVersionID'], DATASET_TYPE_OBJECT);
    $coreVersion = $sender->ConfidenceModel->getCoreVersion();
    
    echo '<div class="Box AddonBox VersionsBox">';
    echo Wrap(T('Community Confidence'), 'h3');
    
    if(!$confidence) {
        echo Wrap(sprintf(T('Vanilla %s: NONE'), $coreVersion->Version), 'p');
    }
    else if ($confidence->TotalVotes > 10 || $confidence->TotalWeight >= 25) {
        echo Wrap(sprintf(T('Vanilla %s: HIGH'), $coreVersion->Version), 'p');
    }
    else {
        echo Wrap(sprintf(T('Vanilla %s: LOW'), $coreVersion->Version), 'p');
    }
  
    writeConfidenceForm($sender->Form, $coreVersion->Version);
    echo '</div>';
}

function writeConfidenceForm($form, $coreVersionID) {
    if(!Gdn::Session()->IsValid()) {
        return;
    }
    
    echo $form->Open();
    echo $form->Errors();
    echo $form->Hidden('CoreVersionID', $coreVersionID);
    echo Wrap(
            Wrap($form->Label('Confidence', 'Weight'), 'h3') .
            $form->TextBox('Weight'),
            'div');
    echo $form->Close('Save');
}