<?php if (!defined('APPLICATION')) exit();
$Session = Gdn::Session();
?>
<div class="SubTitleWrapper">
	<div class="SubTitle">
		<h1>Addons</h1>
	</div>
</div>
<?php
if (!property_exists($this, 'HideSearch')) {
?>
<div class="container_16">
	<div class="grid_12">
		<div class="SearchForm">
			<?php
			$Url = '/addon/browse';
			if (property_exists($this, 'Filter') && $this->Filter != '')
				$Url .= '/'.$this->Filter;
				
			echo $this->Form->Open(array('action' => Url($Url)));
			echo $this->Form->Errors();
			echo $this->Form->TextBox('Keywords');
			echo $this->Form->Button('Browse Addons');
			?>
			<div class="Options">
				<?php
				echo Anchor('Show All Addons', 'addon/browse');
				?>
				or filter to
				<?php
				echo Anchor('Themes', 'addon/browse/themes');
				echo Anchor('Plugins', 'addon/browse/plugins');
				echo Anchor('Applications', 'addon/browse/applications');
				?>
			</div>
			<?php
			echo $this->Form->Close();
			?>
		</div>
	</div>
	<div class="grid_4">
		<div class="UserOptions">
			<h3>Make Your Own Addons!</h3>
			<ul>
			<?php
				echo '<li>'.Anchor('Quick-Start Guide', '/page/AddonQuickStart').'</li>';
				if ($Session->IsValid()) {
					echo '<li>'.Anchor('Upload a New Addon', '/addon/add').'</li>';
				} else {
					echo '<li>'.Anchor('Sign In', '/entry/?Return=/addons').'</li>';
				}
			?>
			</ul>
		</div>
	</div>
</div>
<?php
}