<?php if (!defined('APPLICATION')) exit(); ?>
<div id="Splash">
   <div class="container_12 clearfix">
      <div class="grid_6">
         <?php
            echo Img(
               '/applications/vanillaforumsorg/design/splash-img2.png',
               array(
                  'alt' => 'Vanilla Screenshot'
               )
            );
         ?>
      </div>
      <div class="grid_6">
         <h2>Vanilla is an open-source, standards-compliant, multi-lingual, theme-able, pluggable discussion forum for the web.</h2>
         <p>With over 450 plugins so far, Vanilla is ideal for custom community solutions. Vanilla has been adopted by over <strong>300,000</strong> businesses, brands, and fans.</p>
         <p>You can download and install Vanilla in minutes, or <a href="http://vanillaforums.com">you can host it hassle-free at VanillaForums.com</a>.</p>
         <p class="Download"><?php echo Anchor('Download Vanilla', '/download/', 'DownloadVanilla'); ?></p>
      </div>
   </div>
</div>