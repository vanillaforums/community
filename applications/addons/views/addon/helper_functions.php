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
    <li class="Item AddonItem<?php echo $Alt; ?>">
        <?php
        if ($Addon->Icon != '') {
            echo '<a class="IndexPhoto PhotoWrap" href="'.url($Url).'"><img src="'.Gdn_Upload::url($Addon->Icon).'" /></a>';
        }
        ?>
        <div class="ItemContent ItemContent-addon">
            <?php
            echo anchor(htmlspecialchars($Addon->Name), $Url, 'Title');

            echo '<div class="Description">', sliceString(Gdn_Format::plainText($Addon->Description), 300), '</div>';
            ?>
            <div class="Meta">
                <span class="Tag TypeTag"><?php echo $Addon->Type; ?></span>
                <?php if ($Addon->Type === 'Locale') : ?>
                    <?php if (!is_null($Addon->EnName)) : ?>
                <span class="MItem EnName">
                    Name (en)
                    <span><?php echo htmlspecialchars($Addon->EnName); ?></span>
                </span>
                    <?php endif; ?>
                    <?php if (!is_null($Addon->PercentComplete)) : ?>
                <span class="MItem PercentComplete">
                    Translated
                    <span><?php echo (int)$Addon->PercentComplete.'%'; ?></span>
                </span>
                    <?php endif; ?>
                <?php else : ?>
                <span class="MItem Version">
                    Version
                    <span><?php echo htmlspecialchars($Addon->Version); ?></span>
                </span>
                <?php endif; ?>
                <span class="MItem Author">
                    Author
                    <span><?php echo val('Official', $Addon) ? t('Vanilla Staff') : htmlspecialchars($Addon->InsertName); ?></span>
                </span>
                <span class="MItem Downloads">
                    Downloads
                    <span><?php echo number_format($Addon->CountDownloads); ?></span>
                </span>
                <span class="MItem Updated">
                    Updated
                    <span><?php echo Gdn_Format::date($Addon->DateUpdated, 'html'); ?></span>
                </span>
            </div>
        </div>
    </li>
<?php
}
