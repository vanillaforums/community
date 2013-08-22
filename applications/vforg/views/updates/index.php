<?php if (!defined('APPLICATION')) exit();
echo $this->Form->Open(array('action' => Url('/updates/index')));
?>
<h1><?php echo T('Update Checkers'); ?></h1>
<div class="Info">
   <?php
      echo $this->Form->Errors();
      echo $this->Form->TextBox('Keywords');
      echo $this->Form->Button('Go');
      printf(T('%s sources(s) found.'), $this->Pager->TotalRecords);
      
   ?>
</div>
<table id="Sources" class="AltColumns">
   <thead>
      <tr>
         <th>Location</th>
         <th class="Alt">Ip</th>
         <th>Comments</th>
         <th class="Alt">Discussions</th>
         <th>Users</th>
         <th class="Alt">Last Update Check</th>
      </tr>
   </thead>
   <tbody>
      <?php
      echo $this->Pager->ToString('less');
      include($this->FetchViewLocation('rows'));
      echo $this->Pager->ToString('more');
      ?>
   </tbody>
</table>
<?php
echo $this->Form->Close();