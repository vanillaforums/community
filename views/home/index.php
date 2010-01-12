<?php if (!defined('APPLICATION')) exit(); ?>
<div class="Splash">
   <?php
      $Number = RandomString(1, '0123456');
      $Images = array('discussion', 'discussions', 'themes', 'users', 'dashboard', 'profile');
      $Image = $Images[$Number];
      echo Img(
         '/applications/vforg/design/screen_'.$Image.'.jpg',
         array(
            'alt' => 'Vanilla Screenshot'
         )
      );
      
   ?>
   <div>
      <h2>Vanilla is an open-source, standards-compliant, multi-lingual, theme-able, pluggable discussion forum for the web.</h2>
      <p>With over 450 plugins so far, Vanilla is ideal for custom community solutions. Vanilla has been adopted by over <strong>300,000</strong> businesses, brands, and fans.</p>
      <p>You can download and install Vanilla in minutes, or we can <a href="http://vanillaforums.com">host it hassle-free at VanillaForums.com</a>.</p>
      <p class="ChunkyButton"><?php echo Anchor('Download Vanilla', '/download/', 'DownloadVanilla'); ?></p>
   </div>
</div>