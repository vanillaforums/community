<?php if (!defined('APPLICATION')) exit(); ?>
<div class="Splash">
   <div>
      <h1>The <em>sweetest</em> forum on the web.</h1>
      <h2>Over 300,000 people use Vanilla to build community around their website, brand, or business. For free.</h2>
      <h3>Vanilla is an open-source, standards-compliant, international, customizable, simple, free discussion forum for the web.</h3>
      <?php
         echo Anchor(
            Img('/themes/vanillaforumsorg/design/icons/down.png')
            ."If you are an IT manager, a developer, or a do-it-yourself'er and you have web space, technical know-how, and gumption, click here for the free, downloadable version of Vanilla Forums.",
            '/home/index',
            'Download'
         );
         echo Anchor(
            Img('/themes/vanillaforumsorg/design/icons/promotion_new.png')
            ."If you are a business owner, brand manager, or a non-tech person and you want to have your community up and running in just a few moments with no hassles, click here for free Vanilla Forum hosting.",
            'http://vanillaforums.com',
            'Host'
         );
      ?>
   </div>
</div>