<?php if (!defined('APPLICATION')) exit();
$Session = Gdn::Session();
include($this->FetchViewLocation('helper_functions'));
if ($this->DeliveryType() == DELIVERY_TYPE_ALL)
	echo $this->FetchView('head');
?>
<div id="TranslationHome">
   <div class="container_16">
      <div class="grid_12">
         <h2><?php
         echo $this->RequestMethod == 'mine' ? 'My' : 'All';
         ?> Translations for Vanilla 2</h2>
         <ul class="DataList Languages">
            <?php
            if ($this->LanguageData->NumRows() == 0)
                  echo '<li class="Empty">No translations found.</li>';            

            $Alt = '';
            foreach ($this->LanguageData->Result() as $UserLanaguge) {
               $Alt = $Alt == ' Alt' ? '' : ' Alt';
               WriteUserLanguage($UserLanaguge, $Alt, $this->CountTranslations);
            }
            ?>
         </ul>
      </div>
      <div class="grid_4">
         <div class="Box Contribute">
            <h3>Make your own Translation</h3>
            <p>You can create your own translation right here, or you can get involved by contributing to an existing one. Click the Create a Translation button above, or click the "Contribute" button next to the translation you'd like to help out with!</p>
         </div>
         
         <div class="Box Fork">
            <h3>What does "Fork" mean?</h3>
            <p>You can create your own translation based on an existing one by clicking the "Fork" button next to the appropriate translation. After clicking the button, you will have your own copy of the existing translation that you can edit and release yourself. Think of it as a quick-start to improving on what's already out there.</p>
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