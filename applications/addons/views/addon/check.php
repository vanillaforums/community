<?php if (!defined('APPLICATION')) exit();

/**
 *
 *
 * @param $Data
 */
function _checkTable($Data) {
    echo "<table class='Data' width='100%' style='table-layout: fixed;'>\n";
    echo "<thead><tr><td width='20%'>Field</td><td width='45%'>Current</td><td width='35%'>File</td></tr></thead>";
    $First = true;

    foreach ($Data as $Key => $Value) {
        if (stringBeginsWith($Key, 'File_') || is_array($Value) || $Key == 'Name') {
            continue;
        }

        $Value = Gdn_Format::html($Value);
        $FileValue = Gdn_Format::html(val('File_'.$Key, $Data));

        if ($Key == 'MD5') {
            $Value = substr($Value, 0, 10);
            $FileValue = substr($FileValue, 0, 10);
        }

        if ($Key == 'FileSize') {
            $Value = Gdn_Upload::FormatFileSize($Value);
        }

        echo "<tr><td>$Key</td><td>$Value</td>";

        if ($Error = val('File_Error', $Data)) {
            if ($First) {
                echo '<td rowspan="4">', htmlspecialchars($Error), '</td>';
            }
        } else {
            echo "<td>$FileValue</td></tr>";
        }
        echo "\n";

        $First = false;
    }

    echo '</table>';
}

echo $this->Form->open();
echo $this->Form->errors();

echo anchor('&larr; Back to addon', '/addon/'.$this->data('Addon.AddonID'));
echo '<br /><br />';
echo '<h1>'.$this->data('Addon.Name').'</h1>';
_checkTable($this->data('Addon'));

$AddonID = $this->data('Addon.AddonID');
echo '<br />';

foreach ($this->data('Versions', array()) as $Version) {
    echo '<h2>', t('Version'), ' ', val('Version', $Version), '</h2>';
    _checkTable($Version);
    echo '<p style="text-align: right;">',
        anchor(t('Delete'), "/addon/deleteversion/{$Version['AddonVersionID']}", 'Button Popup'),
        ' ',
        //anchor(t('Save'), "/addon/check/{$AddonID}?SaveVersionID={$Version['AddonVersionID']}", 'Button'),
        '</p>';
}

echo $this->Form->close();