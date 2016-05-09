<?php if (!defined('APPLICATION')) exit();

/**
 *
 *
 * @param $Addon
 * @param $Alt
 */
function writeAddon($Addon, $Alt) {
    $Url = '/addon/'.AddonModel::slug($Addon, FALSE);
    ?>
    <li class="Item AddonRow<?php echo $Alt; ?>">
        <?php
        if ($Addon->Icon != '') {
            echo '<a class="Icon" href="'.url($Url).'"><div class="IconWrap"><img src="'.Gdn_Upload::url($Addon->Icon).'" /></div></a>';
        }
        ?>
        <div class="ItemContent">
            <?php
            $name = ($Addon->Type === 'Locale' && $Addon->EnName != '') ? $Addon->Name.' / '.$Addon->EnName : $Addon->Name;
            echo anchor(htmlspecialchars($name), $Url, 'Title');

            echo '<div class="Description">', anchor(sliceString(Gdn_Format::text($Addon->Description), 300), $Url), '</div>';
            ?>
            <div class="Meta">
                <span class="TypeTag"><?php echo $Addon->Type; ?></span>
                <?php if ($Addon->Type === 'Locale') : ?>
                    <?php if (!is_null($Addon->PercentComplete)) : ?>
                <span class="Completeness">
                    Completeness
                    <span><?php echo (int)$Addon->PercentComplete.'%'; ?></span>
                </span>
                    <?php endif; ?>
                <?php else : ?>
                <span class="Version">
                    Version
                    <span><?php echo htmlspecialchars($Addon->Version); ?></span>
                </span>
                <?php endif; ?>
                <span class="Author">
                    Author
                    <span><?php echo val('Official', $Addon) ? t('Vanilla Staff') : $Addon->InsertName; ?></span>
                </span>
                <span class="Downloads">
                    Downloads
                    <span><?php echo number_format($Addon->CountDownloads); ?></span>
                </span>
                <span class="Updated">
                    Updated
                    <span><?php echo Gdn_Format::date($Addon->DateUpdated); ?></span>
                </span>
            </div>
        </div>
    </li>
<?php
}
