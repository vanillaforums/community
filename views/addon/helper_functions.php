<?php if (!defined('APPLICATION')) exit();

function WriteAddon($Addon, $Alt) {
	$Url = '/addon/'.$Addon->AddonID.'/'.Format::Url($Addon->Name);
	?>
	<li class="AddonRow<?php echo $Alt; ?>">
		<h3><?php echo Anchor($Addon->Name, $Url); ?></h3>
		<?php
		if ($Addon->Icon != '')
			echo '<a class="Icon" href="'.Url($Url).'"><img src="'.Url('uploads/ai'.$Addon->Icon).'" /></a>';

		echo Anchor(SliceString(Format::Html($Addon->Description), 300), $Url);
		?>
		<ul class="Meta">
			<li class="<?php echo $Addon->Vanilla2 == '1' ? 'Vanilla2' : 'Vanilla1'; ?>">
				<span><?php echo $Addon->Vanilla2 == '1' ? 'Vanilla 2' : 'Vanilla 1'; ?></span>
			</li>
			<?php
			if ($Addon->DateReviewed != '') {
				echo '<li class="Approved">
					<span>Approved</span>
				</li>';
			}
			?>
			<li>
				Type
				<span><?php echo $Addon->Type; ?></span>
			</li>
			<li>
				Downloads
				<span><?php echo $Addon->CountDownloads; ?></span>
			</li>
			<li>
				Comments
				<span><?php echo $Addon->CountComments; ?></span>
			</li>
			<li>
				Updated
				<span><?php echo Format::Date($Addon->DateUpdated); ?></span>
			</li>
		</ul>
	</li>
<?php
}