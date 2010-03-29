<?php if (!defined('APPLICATION')) exit(); ?>
<div class="Splash">
   <div>
      <h1>The <em>sweetest</em> forum on the web.</h1>
      <h2>Over 300,000 people use Vanilla to build community around their website, brand, or business. For free.</h2>
      <h3>Vanilla is an open-source, standards-compliant, international, customizable, simple, free discussion forum for the web.</h3>
      <div class="Download">
         <p>If you are an IT manager, a developer, or a do-it-yourselfer and you have web space, technical know-how, and gumption, click here for the free downloadable version of Vanilla Forums.</p>
         <?php
            echo Anchor(
               Img('/themes/vanillaforumsorg/design/icons/down_48.png')
               ."You do the work",
               '/vforg/home/index'
            );
         ?>
      </div>
      <div class="Host">
         <p>If you are a business owner, brand manager, or a non- technical person and you want your community up and running in just a few moments hassle-free, click here for free Vanilla Forum hosting.</p>
         <?php
            echo Anchor(
               Img('/themes/vanillaforumsorg/design/icons/promotion_new_48.png')
               ."We do the work",
               'http://vanillaforums.com'
            );
         ?>
      </div>
   </div>
</div>