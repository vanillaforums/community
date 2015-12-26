<?php if (!defined('APPLICATION')) exit();

/**
 *
 *
 * @param $Data
 */
function _checkTable($Data) {
    echo "<table class='Data' width='100%' style='table-layout: fixed;'>\n";
    echo "<thead><tr><td width='30%'>Field</td><td width='35%'>Current</td><td width='35%'>File</td></tr></thead>";
    $Alt = true;
    $First = true;

    foreach ($Data as $Key => $Value) {
        if (StringBeginsWith($Key, 'File_')) {
            continue;
        }

        $Value = Gdn_Format::html($Value);
        $FileValue = Gdn_Format::html(val('File_'.$Key, $Data));

        if ($Key == 'MD5') {
            $Value = substr($Value, 0, 10);
            $FileValue = substr($FileValue, 0, 10);
        }

        $Class = '';
        if ($Alt) {
            $Class = ' class="Alt"';
        }

        echo "<tr{$Class}><th>$Key</th><td>$Value</td>";

        if ($Error = val('File_Error', $Data)) {
            if ($First) {
                echo '<td rowspan="4">', htmlspecialchars($Error), '</td>';
            }
        } else {
            echo "<td>$FileValue</td></tr>";
        }

        echo "\n";

        $Alt = !$Alt;
        $First = false;
    }

    echo '</table>';
}

echo $this->Form->open();
echo $this->Form->errors();

echo anchor('Back to Addon', '/addon/'.$this->data('Addon.AddonID'));

echo '<h2>Addon</h2>';
_checkTable($this->data('Addon'));

$AddonID = $this->data('Addon.AddonID');
foreach ($this->data('Versions', array()) as $Version) {
    echo '<h2>', t('Version'), ' ', val('Version', $Version), '</h2>';
    _checkTable($Version);
    echo '<p style="text-align: right;">',
        anchor(t('Delete'), "/addon/deleteversion/{$Version['AddonVersionID']}", 'Button Popup'),
        ' ',
        anchor(t('Save'), "/addon/check/{$AddonID}?SaveVersionID={$Version['AddonVersionID']}", 'Button'),
        '</p>';
}

echo $this->Form->close();