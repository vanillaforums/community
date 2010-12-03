<?php if (!defined('APPLICATION')) exit(); ?>
<div class="Splash">
   <div class="SplashMessage">
      <h2>The best way to make your community grow.</h2>
      <p>
         <i class="Sprite SpriteScreen"></i>
         Vanilla Forums are open-source, standards-compliant, customizable discussion forums.
      </p>
      <p>
         <i class="Sprite SpriteSmile"></i> Migrate from any platform today, and join over <span><?php
         $Version = GetValue('Version', $this->Data, '2.0');
         $DateUploaded = GetValue('DateUploaded', $this->Data, '2010-07-21 00:00:00');
         $CountDownloads = GetValue('CountDownloads', $this->Data);
         if (is_numeric($CountDownloads) && $CountDownloads > 350000)
            echo number_format($CountDownloads);
         else
            echo '350,000';
         ?></span> sites using Vanilla Forums for effective community growth.
      </p>
      <?php echo Anchor('<strong>Get Your Vanilla Forum Now</strong> Vanilla '.$Version.' - Released '.Gdn_Format::Date($DateUploaded), 'download', 'Get'); ?>
   </div>
   <div class="SplashPreview">
      <div class="Window">
         <div class="Reel">
         <?php
            $Images = array('screen-1.png', 'screen-2.png', 'screen-3.png', 'screen-4.png', 'screen-5.png', 'screen-6.png', 'screen-7.png', 'screen-8.png');
            for ($i = 0; $i < count($Images); $i++) {
               echo Img('/applications/vforg/design/images/'.$Images[$i], array('alt' => 'Vanilla Screenshot', 'height' => '361', 'width' => '539'));
            }
         ?>
         </div>
      </div>
      <div class="ScreenNav">
      <?php
         $On = 'On';
         for ($i = 0; $i < count($Images); $i++) {
            echo Anchor('<i class="Sprite Sprite'.($i+1).' SpriteDot SpriteDot'.$On.'"></i>', '#', array('rel' => $i+1));
            $On = 'Off';
         }
      ?>
      </div>
   </div>
   
   <div class="NewsAndEvents">
      <div class="News">
         <h3>News from Vanilla Forums</h3>
         <?php echo ProxyRequest(Gdn::Request()->Url('vforg/home/getfeed/blog?DeliveryType=VIEW', TRUE)); ?>
      </div><div class="Events">
         <h3>Upcoming Vanilla Forums Events</h3>
         <?php echo ProxyRequest(Gdn::Request()->Url('vforg/home/getfeed/events/?DeliveryType=VIEW', TRUE)); ?>
      </div>
   </div>
   
   <div class="Foot Wrapper">
      <?php
      echo Anchor('Free Community Support', '/discussions');
      echo Anchor('Paid Support', '/services');
      echo Anchor('Addons', '/addons');
      echo Anchor('Contact Us', '/page/contact');
      echo Anchor('Get a Free Hosted Vanilla Forum', 'http://vanillaforums.com');
      ?>
   </div>
</div>