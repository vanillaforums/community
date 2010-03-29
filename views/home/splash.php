<?php if (!defined('APPLICATION')) exit(); ?>
<div class="Splash">
   <div>
      <h1>The <em>sweetest</em> forum on the web.</h1>
      <h2>Over 300,000 customers use Vanilla to grow community around their website, brand, or business. For free.</h2>
      <!--
      <h3>Vanilla is an open-source, standards-compliant, international, customizable, simple, free discussion forum for the web.</h3>
      -->
      <div class="Host">
         <?php
            echo Anchor(
               Img('/themes/vanillaforumsorg/design/icons/promotion_new_48.png')
               ."If you are a business owner, brand manager, or a non- technical person and you want your community up and running in just a few moments hassle-free, click here for free Vanilla Forum hosting.",
               'http://vanillaforums.com'
            );
         ?>
      </div>
      <div class="Download">
         <?php
            echo Anchor(
               Img('/themes/vanillaforumsorg/design/icons/down_48.png')
               ."If you are an IT manager, a developer, or a do-it-yourselfer and want to install Vanilla Forums in your own environtment, click here for the open source download.",
               '/vforg/home/index'
            );
         ?>
      </div>
   </div>
</div>