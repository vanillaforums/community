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
            echo anchor($Addon->Name, $Url, 'Title');

            echo '<div class="Description">', anchor(sliceString(Gdn_Format::text($Addon->Description), 300), $Url), '</div>';
            ?>
            <div class="Meta">
                <span class="<?php echo $Addon->Vanilla2 == '1' ? 'Vanilla2' : 'Vanilla1'; ?>"><?php
                    echo $Addon->Vanilla2 == '1' ? 'Vanilla 2' : 'Vanilla 1'; ?></span>
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
                    <span><?php echo Gdn_Format::date($Addon->DateUpdated); ?></span>
                </span>
            </div>
        </div>
    </li>
<?php
}
