<?php if (!defined('APPLICATION')) exit();

function WriteAddon($Addon, $Alt) {
   $Url = '/addon/'.AddonModel::Slug($Addon, FALSE);
	?>
	<li class="Item AddonRow<?php echo $Alt; ?>">
		<div class="ItemContent">
			<?php
			echo '<div>', Anchor($Addon->Name, $Url, 'Title'), '</div>';
			
			if ($Addon->Icon != '') {
				echo '<div><a class="Icon" href="'.Url($Url).'"><div class="IconWrap"><img src="'.Gdn_Upload::Url($Addon->Icon).'" /></div></a></div>';
         }
	
			echo '<div>', Anchor(SliceString(Gdn_Format::Text($Addon->Description), 300), $Url), '</div>';
			?>
			<div class="Meta">
				<span class="<?php echo $Addon->Vanilla2 == '1' ? 'Vanilla2' : 'Vanilla1'; ?>"><?php
					echo $Addon->Vanilla2 == '1' ? 'Vanilla 2' : 'Vanilla 1'; ?></span>
				<?php
            if (Gdn::Session()->CheckPermission('Addons.Addon.Manage')) {
               if ($Addon->Checked) {
                  echo '<span class="Approved">Checked</span>';
               } elseif ($Addon->Vanilla2) {
                  echo Anchor('<span class="Closed">Check</span>', Url('/addon/check/'.$Addon->AddonID));
               }
            }
				if ($Addon->DateReviewed != '')
					echo '<span class="Approved">Approved</span>';
				?>
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
					<span><?php echo Gdn_Format::Date($Addon->DateUpdated); ?></span>
				</span>
			</div>
		</div>
	</li>
<?php
}
