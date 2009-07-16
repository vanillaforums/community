<?php if (!defined('APPLICATION')) exit();
include($this->FetchViewLocation('helper_functions'));
if ($this->DeliveryType() == DELIVERY_TYPE_ALL)
	echo $this->FetchView('head');
   
?>
<div id="AddonHome">
   <div class="container_16">
      <div class="grid_12">
         <?php
         if ($this->ApprovedData->NumRows() > 0) {
         ?>
         <h2>Vanilla Approved</h2>
         <ul class="DataList Addons">
            <?php
            $Alt = '';
            foreach ($this->ApprovedData->Result() as $Addon) {
               $Alt = $Alt == ' Alt' ? '' : ' Alt';
               WriteAddon($Addon, $Alt);
            }
            ?>
         </ul>
         <?php
         }
         
         if ($this->NewData->NumRows() > 0) {
         ?>
         <h2>Recently Uploaded &amp; Updated</h2>
         <ul class="DataList Addons">
            <?php
            $Alt = '';
            foreach ($this->NewData->Result() as $Addon) {
               $Alt = $Alt == ' Alt' ? '' : ' Alt';
               WriteAddon($Addon, $Alt);
            }
            ?>
         </ul>
         <?php
         echo Anchor('More', '/addon/browse', array('class' => 'More'));
         }
         ?>
      </div>
      <div class="grid_4">
         <div class="Box Blue1">
            <h3>What is this stuff?</h3>
            <p>Addons are custom features that you can add to your Vanilla forum. Addons are created by our community of developers and people like you!</p>
         </div>
         
         <div class="Box Blue2">
            <h3>Vanilla Approved?</h3>
            <p>We review addons to make sure they are safe and don't cause bugs. An addon is considered to be "Vanilla Approved" once our review process is complete.</p>
         </div>

         <div class="UserOptions">
            <h3>Don't have Vanilla yet?</h3>
            <ul>
               <li><?php echo Anchor('Download Vanilla Now', '/download'); ?></li>
            </ul>
         </div>
      </div>
      <div class="clearfix"></div>
   </div>
</div>


