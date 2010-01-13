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
      <h1>The <em>sweetest</em> forum on the web.</h1>
      <h2>Vanilla is an open-source, standards-compliant, multi-lingual, theme-able, pluggable discussion forum for the web.</h2>
      <p>With over 450 plugins so far, Vanilla is ideal for custom community solutions. Vanilla has been adopted by over <strong>300,000</strong> businesses, brands, and fans.</p>
      <p class="ChunkyButton"><?php echo Anchor('Download Vanilla', '/download/', 'DownloadVanilla'); ?></p>
      <p class="Option">↳ Or let us host it for you at <a href="http://vanillaforums.com">VanillaForums.com</a></p>
   </div>
</div>