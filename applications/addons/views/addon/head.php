<?php if (!defined('APPLICATION')) exit();

$filterClass = "AddonSearch-filterOption ";
if (!property_exists($this, 'HideSearch')) {
?>
<div class="SearchForm AddonSearch">
    <?php
    $Filter = ($this->Filter == 'plugins,applications') ? 'apps' : $this->Filter;
    $Url = '/addon/browse/'.$Filter.'/';
    $Query = GetIncomingValue('Keywords', '');
    echo $this->Form->open(array('action' => url($Url.$this->Sort)));
    echo $this->Form->errors();
    echo "<div class='AddonSearch-searchBox'>";
    echo $this->Form->textBox('Keywords', array('value' => $Query));
    echo $this->Form->button('Search Addons');
    echo "</div>";

    $Query = urlencode($Query);
    if ($Query != '')
        $Query = '?Keywords='.$Query;
    ?>
    <div class="Options AddonSearch-filters">
        <div class="AddonSearch-filter">
        <strong class="AddonSearch-filterTitle">Filter:</strong>
        <?php
            $suffix = $this->Sort.'/'.$Query;

            echo anchor(
                'Everything',
                'addon/browse/all/' . $suffix,
                $filterClass . ('ShowAll' . ($this->Filter == 'all' ? ' Active' : ''))
            );
            echo anchor(
                'Plugins',
                'addon/browse/apps/' . $suffix,
                $filterClass . ($this->Filter == 'plugins,applications' ? 'Active' : '')
            );
            echo anchor(
                'Themes',
                'addon/browse/themes/' . $suffix,
                $filterClass . ($this->Filter == 'themes' ? 'Active' : '')
            );
            echo anchor(
                'Locales',
                'addon/browse/locales/' . $suffix,
                $filterClass . ($this->Filter == 'locales' ? 'Active' : '')
            );
            echo anchor(
                'Official',
                'addon/browse/core/' . $suffix,
                $filterClass . ($this->Filter == 'core' ? 'Active' : '')
            );
        ?>
        </div>

        <div class="AddonSearch-filter">
        <strong class="AddonSearch-filterTitle">Sort:</strong>
        <?php
        echo anchor(
            'Last Updated',
            $Url.'recent/'.$Suffix,
            $filterClass . ($this->Sort == 'recent' ? 'Active' : '')
        );
        echo anchor(
            'Most Downloads',
            $Url.'popular/'.$Suffix,
            $filterClass . ($this->Sort == 'popular' ? 'Active' : '')
        );
        ?>
        </div>
    </div>
    <?php
    echo $this->Form->close();
    ?>
</div>
<?php
}