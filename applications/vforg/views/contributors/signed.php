<?php if (!defined('APPLICATION')) exit(); ?>
<div class="SubTitleWrapper">
   <div class="SubTitle">
      <h1>Members who have filled out the Vanilla Forums Contributor Agreement</h1>
   </div>
</div>
<table>
   <thead>
   <tr>
      <td>Name</td>
      <td>Date</td>
   </tr>
   </thead>
   <tbody>
   <?php
foreach ($this->UserData->Result() as $User) {
		?>
		<tr>
			<td><?php echo $User->Name; ?></td>
			<td><?php echo date("m/d/y", Gdn_Format::ToTimeStamp($User->DateContributorAgreement)); ?></td>
		</tr>
		<?php
}
   ?>
   </tbody>
</table>