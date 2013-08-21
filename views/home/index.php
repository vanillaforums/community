<?php if (!defined('APPLICATION')) exit();
$this->Title('Community Forum Software');
$Version = GetValue('Version', $this->Data, '2.0');
$DateUploaded = GetValue('DateUploaded', $this->Data, '2010-07-21 00:00:00');
$CountDownloads = GetValue('CountDownloads', $this->Data);
function Alt($Alt) {
   return array('alt' => $Alt, 'title' => $Alt);
}
?>
<script type="text/javascript">
gdn = {};
gdn.definition = function() {};
jQuery(document).ready(function($) {
   $("#Slider").easySlider({
         controlsContainer: '.SplashNav',
			speed: 			400,
			auto:			true,
			pause:			20000,
			continuous:		true, 
			numeric: 		true,
			numericId: 		'controls'
   });
});
</script>
<div class="Head">
   <div class="Row">
      <span class="OpenSource"></span>
      <?php
      echo Anchor('Vanilla Forums: Community Forums Evolved', '/', array('class' => 'Home'));
      echo '<div class="Menu">';
         echo Anchor('Addons', 'addons');
         echo Anchor('Community', 'discussions');
         echo Anchor('Documentation', 'docs');
         echo Anchor('Blog', 'http://vanillaforums.com/blog');
         echo Anchor('Looking for our cloud software?', 'http://vanillaforums.com', 'GreenButton');
//echo Anchor('Vanilla SaaS'.Wrap('Looking for our hosted product?'), 'http://vanillaforums.com', array('class' => 'Hosting'));
         // echo Anchor('Download', 'download', array('class' => 'Download'));
      echo '</div>';
      ?>
   </div>
</div>
<div class="AboveFold">
   <div class="Row">
      <h1>Community Forums Evolved.</h1>
      <div class="SplashSlider">
         <div id="Slider">
            <ul>
               <li>
                  <div class="Splash"><?php echo Img('applications/vforg/design/images/custom-themes.png', Alt('Amazing Themes')); ?></div>
               </li>
               <li>
                  <div class="Splash"><?php echo Img('applications/vforg/design/images/badges-and-reactions.png', Alt('Badges & Reactions')); ?></div>
               </li>
               <li>
                  <div class="Splash"><?php echo Img('applications/vforg/design/images/feature-community-analytics.png', Alt('Community Analytics')); ?></div>
               </li>
               <li>
                  <div class="Splash"><?php echo Img('applications/vforg/design/images/vanilla-on-iphones.png', Alt('Works Natively on any Mobile Device')); ?></div>
               </li>
            </ul>
         </div>
      </div>
      <div class="AboutVanilla">
         <div class="SplashNav"></div>
      </div>
   </div>
</div>
<div class="Fold"><div class="Row"></div></div>
<div class="BelowFold">
   <div class="Row">
      <div class="Col Col1 HostingCol">
         <h2>Vanilla Cloud Platform</h2>
         <ul>
            <li><?php echo Sprite('SpCheck'); ?> Optimized for speed</li>
            <li><?php echo Sprite('SpCheck'); ?> Premium features</li>
            <li><?php echo Sprite('SpCheck'); ?> Customer Support</li>
            <li><?php echo Sprite('SpCheck'); ?> Migration Services</li>
         </ul>
         <p class="HostInfo">
            <strong>Always Stable</strong>
            <br />
            <?php echo Anchor('30-Day Free Trial', 'http://vanillaforums.com/plans', 'GreenButton'); ?>
            &nbsp;
            <?php echo Anchor('Take a Tour', 'http://vanillaforums.com/features', 'PurpleButton'); ?>
         </p>
      </div>
      <div class="Col Col2 OpenSourceCol">
         <h2>Vanilla Open Source</h2>
         <ul>
            <li><?php echo Sprite('SpCheck'); ?> Free Download</li>
            <li><?php echo Sprite('SpFlat'); ?> You Host It</li>
            <li><?php echo Sprite('SpFrown'); ?> You Optimize It</li>
            <li><?php echo Sprite('SpSad'); ?> You Break It, You Fix It</li>
         </ul>
         <p class="DownloadInfo">
            <strong>Latest Stable Download: <?php echo Anchor('Version '.$Version, 'download'); ?></strong>
            <br />Released: <?php echo Gdn_Format::Date($DateUploaded); ?>
            <br /><?php echo Anchor('System Requirements', 'docs/installation-requirements'); ?>
         </p>
      </div>
   </div>
</div>
<div class="Fold"><div class="Row"></div></div> 
<div class="Row">
        <div class="AboutFold Col3">
          <p><strong>We believe that online communities should be 
            intuitive, engaging and true to your brand.</strong> 
            Vanilla allows you to create a 
            customized community that rewards positive participation, 
            automatically curates content and lets members drive 
            moderation. 
         </p></div>    
      <div class="AboutFold Col4">
         <p>
            Vanilla provides cloud and open source community forum 
            software that powers discussion forums on <?php
            if (is_numeric($CountDownloads) && $CountDownloads > 500000)
               echo number_format($CountDownloads);
            else
               echo 'over 500,000';
            ?> sites. Built for flexibility and integration, <strong>Vanilla is 
            the best, most powerful community solution in the world.</strong>
         </p></div>
 </div>
 
<div class="Foot">
   <div class="Row">
      <div class="Cred">
         <label>Awesome companies use Vanilla's community forum software:</label><br><br>
         <strong>9to5 Mac, HubSpot, Corptax, O'Reilly Media, Boagworld, Car Talk, Penny Arcade, Mozilla, and plenty more.</strong>
      </div>
   </div>
</div>