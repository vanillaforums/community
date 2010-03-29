<?php if (!defined('APPLICATION')) exit(); ?>
<div class="Splash">
   <div>
      <h1>The <em>sweetest</em> forum on the web.</h1>
      <h2>Over 300,000 people use Vanilla to build community around their website, brand, or business. For free.</h2>
      <h3>Vanilla is an open-source, standards-compliant, international, customizable, simple, free discussion forum for the web.</h3>
      <?php
         echo Anchor("If you are an IT manager, a developer, or a DIY'er and you have web space, technical know-how, and gumption, click here for more information on the free, downloadable version of Vanilla.", '/home/index', 'Download');
         echo Anchor("If you are a business owner, a brand manager, or a non-technical person and you want to have a Vanilla forum up and running in just a few moments with zero pain, click here for free Vanilla hosting.", 'http://vanillaforums.com', 'Host');
      ?>
   </div>
</div>