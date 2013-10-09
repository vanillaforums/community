<?php if (!defined('APPLICATION')) exit();
$Session = Gdn::Session();
include($this->FetchViewLocation('helper_functions'));

if ($this->DeliveryType() == DELIVERY_TYPE_ALL) {
   echo $this->FetchView('head');
?>
   <h1><?php echo $this->Data('Title'); ?></h1>
   <ul class="DataList Addons">
      <?php
      if ($this->Data('Addons')->NumRows() == 0)
         echo '<li class="Empty">There were no addons matching your search criteria.</li>';
}            
$Alt = '';
foreach ($this->Data('Addons')->Result() as $Addon) {
   $Alt = $Alt == ' Alt' ? '' : ' Alt';
   WriteAddon($Addon, $Alt);
}
if ($this->DeliveryType() == DELIVERY_TYPE_ALL && $this->Data('_Pager')) {
?>
   </ul>
   <?php
   echo $this->Data('_Pager')->ToString('more');
} else {
?></ul><?php
}