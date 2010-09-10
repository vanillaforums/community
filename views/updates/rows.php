<?php if (!defined('APPLICATION')) exit();
$Alt = FALSE;
foreach ($this->UpdateData->Result('Text') as $Update) {
   $Alt = $Alt ? FALSE : TRUE;
   ?>
   <tr<?php echo $Alt ? ' class="Alt"' : ''; ?>>
      <td><?php echo Anchor($Update->Location, $Update->Location); ?></td>
      <td class="Alt"><?php echo $Update->RemoteIp; ?></td>
      <td><?php echo number_format($Update->CountComments; ?></td>
      <td class="Alt"><?php echo number_format($Update->CountDiscussions); ?></td>
      <td><?php echo number_format($Update->CountUsers); ?></td>
      <td class="Alt"><?php echo Gdn_Format::Date($Update->DateInserted); ?></td>
   </tr>
<?php
}