<?php if (!defined('APPLICATION')) exit();
$Session = Gdn::Session();

if ($this->DeliveryType() == DELIVERY_TYPE_ALL) {
	echo $this->FetchView('head');
?>
<div id="AddonPage">
	<div id="Content" class="container_16">
		<div class="grid_16">
			<h2>
				<?php echo Anchor('Addons', '/addon/browse/'); ?>
				<span>&bull;</span> <?php echo Anchor($this->Addon->Type.'s', '/addon/browse/'.strtolower($this->Addon->Type).'s'); ?>
				<span>&bull;</span> <?php echo $this->Addon->Name; ?>
				<?php echo $this->Addon->Version; ?>
			</h2>
		</div>
		<div class="grid_12">
			<?php
			if ($this->Addon->DateReviewed == '')
				echo '<div class="Warning"><strong>Warning!</strong> We have not performed any code-review or testing on this addon. Use it at your own risk!</div>';
			
			if ($this->Addon->Icon != '')
				echo '<img class="Icon" src="'.Url('uploads/ai'.$this->Addon->Icon).'" />';
				
			echo Format::Html($this->Addon->Description);
			if ($this->PictureData->NumRows() > 0) {
				?>
				<div class="PictureBox">
					<?php
					foreach ($this->PictureData->Result() as $Picture) {
						echo '<a rel="popable[gallery]" href="'.Url('/uploads/ao'.$Picture->File).'"><img src="'.Url('uploads/at'.$Picture->File).'" /></a>';
					}
					?>
				</div>
				<?php
			}
			?>
			<h2>Comments</h2>
			<ul id="Discussion">
			<?php
			if ($this->CommentData->NumRows() == 0)
				echo '<li class="Empty">No-one has commented on this addon yet.</li>';
}			
$CurrentOffset = 0;
foreach ($this->CommentData->Result() as $Comment) {
	++$CurrentOffset;
	?>
	<li class="Comment" id="Comment_<?php echo $Comment->AddonCommentID; ?>">
		<a name="Item_<?php echo $CurrentOffset;?>" />
		<ul class="Info<?php echo ($Comment->InsertUserID == $Session->UserID ? ' Mine' : '') ?>">
			<li class="Author">
				<?php 
				echo UserPhoto($Comment->InsertName, $Comment->InsertPhoto);
				echo UserAnchor($Comment->InsertName);
				?>
			</li>
			<li class="Created">
				<?php
				echo Format::Date($Comment->DateInserted);
				?>
			</li>
		</ul>
		<?php
		if ($Session->CheckPermission('Garden.Activity.Delete'))
			echo Anchor('Delete', '/addon/deletecomment/'.$Comment->AddonCommentID.'/'.$Session->TransientKey().'?Return='.urlencode(Gdn_Url::Request()), 'DeleteComment');

		?>
		<div class="Body"><?php echo Format::To($Comment->Body, $Comment->Format); ?></div>
	</li>
	<?php
}
if ($this->DeliveryType() == DELIVERY_TYPE_ALL) {
	if ($this->CommentData->NumRows() > 0)
		echo '</ul>';
	
			echo $this->Pager->ToString('more');
			
			// Write out the comment form
			if ($Session->IsValid()) {
				?>
				<div id="CommentForm">
					<?php
						$this->Form->SetModel($this->AddonCommentModel);
						$this->Form->AddHidden('AddonID', $this->Addon->AddonID);
						$this->Form->Action = Url('/addon/addcomment');
						echo $this->Form->Open();
						echo $this->Form->Errors();
						echo $this->Form->Label('Add Comment', 'Body', array('class' => 'Heading'));
						echo $this->Form->Errors();
						echo $this->Form->TextBox('Body', array('MultiLine' => TRUE));
						echo $this->Form->Button('Post Comment');
						echo $this->Form->Close();
					?>
				</div>
				<?php
			} else {
				?>
				<div class="CommentOption">
					<?php echo Gdn::Translate('Want to take part in this discussion? Click one of these:'); ?>
					<?php echo Anchor('Sign In', '/entry/?Target='.urlencode($this->SelfUrl), 'Button'); ?> 
					<?php echo Anchor('Register For Membership', '/entry/?Target='.urlencode($this->SelfUrl), 'Button'); ?>      
				</div>
				<?php 
			}
		?>
		</div>
		<div class="grid_4 Panel">
			<?php
			if (property_exists($this, 'SideMenu'))
				echo $this->SideMenu->ToString();
			?>
			<div class="Box DownloadBox">
				<?php echo Anchor('Download', '/get/'.$this->Addon->AddonID); ?>
				<dl>
					<dt>Version</dt>
					<dd><?php echo $this->Addon->Version.'&nbsp;'; ?></dd>
					<dt>Released</dt>
					<dd><?php echo Format::Date($this->Addon->DateUploaded); ?></dd>
					<dt>Downloads</dt>
					<dd><?php echo $this->Addon->CountDownloads; ?></dd>
				</dl>
			</div>
			<?php
			if ($this->Addon->Requirements != '') {
			?>
			<div class="Box Requirements">
				<h3>Requirements</h3>
				<?php echo Format::Display($this->Addon->Requirements); ?>
			</div>
			<?php
			}
			if ($this->Addon->TestedWith != '') {
			?>
			<div class="Box Testing">
				<h3>Tested With</h3>
				<?php echo Format::Display($this->Addon->TestedWith); ?>
			</div>
			<?php
			}
			?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<?php
}