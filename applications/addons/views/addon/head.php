<?php if (!defined('APPLICATION')) exit();

if (!property_exists($this, 'HideSearch')) {
?>
<div class="SearchForm">
    <?php
    $Url = '/addon/browse/'.$this->Filter.'/';
    $Query = GetIncomingValue('Keywords', '');
    echo $this->Form->open(array('action' => Url($Url.$this->Sort.'/'.$this->Version)));
    echo $this->Form->errors();
    echo $this->Form->textBox('Keywords', array('value' => $Query));
    echo $this->Form->button('Browse Addons');

    $Query = urlencode($Query);
    if ($Query != '')
        $Query = '?Keywords='.$Query;
    ?>
    <div class="Options">
        <strong>↳</strong>
        <?php
        $Suffix = $this->Sort.'/'.$this->Version.'/'.$Query;
        echo anchor('Show All Addons', 'addon/browse/all/'.$Suffix, 'ShowAll' . ($this->Filter == 'all' ? ' Active' : ''));
        ?>
        or filter to
        <?php
        echo anchor('Core', 'addon/browse/core/'.$Suffix, $this->Filter == 'core' ? 'Active' : '');
        echo anchor('Themes', 'addon/browse/themes/'.$Suffix, $this->Filter == 'themes' ? 'Active' : '');
        echo anchor('Plugins', 'addon/browse/plugins/'.$Suffix, $this->Filter == 'plugins' ? 'Active' : '');
        echo anchor('Applications', 'addon/browse/applications/'.$Suffix, $this->Filter == 'applications' ? 'Active' : '');
        echo anchor('Locales', 'addon/browse/locales/'.$Suffix, $this->Filter == 'locales' ? 'Active' : '');
        ?>
    </div>
    <div class="Options">
        <strong>↳</strong> Show addons for
        <?php
        $CssClass = $this->Version == '2' ? 'Active' : '';
        echo anchor('Vanilla 2', $Url.$this->Sort.'/2/'.$Query, $CssClass);
        $CssClass = $this->Version == '1' ? 'Active' : '';
        echo anchor('Vanilla 1', $Url.$this->Sort.'/1/'.$Query, $CssClass);
        ?>
    </div>
    <div class="Options OrderOptions">
        <strong>↳</strong> Order by
        <?php
        $Suffix = $this->Version.'/'.$Query;
        echo anchor('Recent', $Url.'recent/'.$Suffix, $this->Sort == 'recent' ? 'Active' : '');
        echo anchor('Popular', $Url.'popular/'.$Suffix, $this->Sort == 'popular' ? 'Active' : '');
        ?>
    </div>
    <?php
    echo $this->Form->close();
    ?>
</div>
<?php
}