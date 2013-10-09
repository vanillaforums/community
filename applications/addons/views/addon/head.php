<?php if (!defined('APPLICATION')) exit();
$Session = Gdn::Session();
if (!property_exists($this, 'HideSearch')) {
?>
<div class="SearchForm">
	<?php
	$Url = '/addon/browse/'.$this->Filter.'/';
	$Query = GetIncomingValue('Keywords', '');
	echo $this->Form->Open(array('action' => Url($Url.$this->Sort.'/'.$this->Version)));
	echo $this->Form->Errors();
	echo $this->Form->TextBox('Keywords', array('value' => $Query));
	echo $this->Form->Button('Browse Addons');
   
   $Query = urlencode($Query);
	if ($Query != '')
		$Query = '?Keywords='.$Query;
	?>
	<div class="Options">
		<strong>↳</strong>
		<?php
		$Suffix = $this->Sort.'/'.$this->Version.'/'.$Query;
		echo Anchor('Show All Addons', 'addon/browse/all/'.$Suffix, 'ShowAll' . ($this->Filter == 'all' ? ' Active' : ''));
		?>
		or filter to
		<?php
      echo Anchor('Core', 'addon/browse/core/'.$Suffix, $this->Filter == 'core' ? 'Active' : '');
		echo Anchor('Themes', 'addon/browse/themes/'.$Suffix, $this->Filter == 'themes' ? 'Active' : '');
		echo Anchor('Plugins', 'addon/browse/plugins/'.$Suffix, $this->Filter == 'plugins' ? 'Active' : '');
		echo Anchor('Applications', 'addon/browse/applications/'.$Suffix, $this->Filter == 'applications' ? 'Active' : '');
      echo Anchor('Locales', 'addon/browse/locales/'.$Suffix, $this->Filter == 'locales' ? 'Active' : '');
		?>
	</div>
	<div class="Options">
		<strong>↳</strong> Show addons for
		<?php
// $CssClass = $this->Version == '0' ? 'Active' : '';
// echo Anchor('Both Vanilla Versions', $Url.$this->Sort.'/0/'.$Query, $CssClass);
		$CssClass = $this->Version == '2' ? 'Active' : '';
		echo Anchor('Vanilla 2', $Url.$this->Sort.'/2/'.$Query, $CssClass);
		$CssClass = $this->Version == '1' ? 'Active' : '';
		echo Anchor('Vanilla 1', $Url.$this->Sort.'/1/'.$Query, $CssClass);
		?>
	</div>
	<div class="Options OrderOptions">
		<strong>↳</strong> Order by
		<?php
		$Suffix = $this->Version.'/'.$Query;
		echo Anchor('Recent', $Url.'recent/'.$Suffix, $this->Sort == 'recent' ? 'Active' : '');
		echo Anchor('Popular', $Url.'popular/'.$Suffix, $this->Sort == 'popular' ? 'Active' : '');
		?>
	</div>
	<?php
	echo $this->Form->Close();
	?>
</div>
<?php
}