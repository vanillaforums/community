<?php if (!defined('APPLICATION')) exit();

$this->Title(T("Community Forum Software"));

$Version        = GetValue('Version', $this->Data, '2.0');
$DateUploaded   = GetValue('DateUploaded', $this->Data, '2010-07-21 00:00:00');
$CountDownloads = GetValue('CountDownloads', $this->Data);

function Alt($Alt) {
   return array('alt' => $Alt, 'title' => $Alt);
}

if (is_numeric($CountDownloads) && $CountDownloads > 500000) {
   $Installtions = number_format($CountDownloads);
}
else {
   $Installtions = 'over 500,000';
}

?>

<script type="text/javascript">
   gdn = {};
   gdn.definition = function() {};

   jQuery(function() {
      $('#Slider').easySlider({
         controlsContainer : '.SplashNav',
         speed             : 400,
         auto              : true,
         pause             : 20000,
         continuous        : true,
         numeric           : true,
         numericId         : 'controls'
      });
   });
</script>

<div class="Head">
   <div class="Row">
      <span class="OpenSource"></span>
      <?php
      echo Anchor(T("Vanilla Forums: Community Forums Evolved"), '/', array('class' => 'Home'));
      echo '<div class="Menu">';
         echo Anchor(T("Addons"), 'addons', array('title' => T("Themes, plugins and applications for Vanilla")));
         echo Anchor(T("Community"), 'discussions', array('title' => T("Vanilla Forums Developer Community")));
         echo Anchor(T("Documentation"), 'docs', array('title' => T("Developer documentation for Vanilla")));
         echo Anchor(T("Blog"), 'http://vanillaforums.com/blog', array('title' => T("Latest news from the Vanilla Team")));
         echo Anchor(T("Looking for our cloud software?"), 'http://vanillaforums.com', array('class' => 'Button Green', 'title' => T("Use Vanilla Forums in the Cloud")));
      echo '</div>';
      ?>
   </div>
</div>

<div class="Row">
   <h1>Community Forums Evolved.</h1>
   <div class="SplashSlider">
      <div id="Slider">
         <ul>
            <li><div class="Splash"><?php echo Img('applications/vforg/design/images/custom-themes.png', Alt(T("Amazing Themes"))); ?></div></li>
            <li><div class="Splash"><?php echo Img('applications/vforg/design/images/badges-and-reactions.png', Alt(T("Badges & Reactions"))); ?></div></li>
            <li><div class="Splash"><?php echo Img('applications/vforg/design/images/feature-community-analytics.png', Alt(T("Community Analytics"))); ?></div></li>
            <li><div class="Splash"><?php echo Img('applications/vforg/design/images/vanilla-on-iphones.png', Alt(T("Works Natively on any Mobile Device"))); ?></div></li>
         </ul>
      </div>
   </div>
   <div class="SplashNav"></div>
</div>

<div class="Row">
   <div class="Columns">
      <div class="Column">
         <h2>Vanilla Cloud</h2>
         <ul class="Features">
            <li><?php echo Sprite('SpCheck'); ?> Optimized for speed</li>
            <li><?php echo Sprite('SpCheck'); ?> Premium features</li>
            <li><?php echo Sprite('SpCheck'); ?> Customer Support</li>
            <li><?php echo Sprite('SpCheck'); ?> Migration Services</li>
         </ul>
         <div class="Info">
            <h3>Always Stable</h3>
            <ul class="Buttons">
               <li><?php echo Anchor('30-Day Free Trial', 'http://vanillaforums.com/plans', array('class' => 'Button Green', 'title' => T("Sign up for a 30-day free trial of Vanilla Cloud"))); ?></li>
               <li><?php echo Anchor('Take a Tour', 'http://vanillaforums.com/features', array('class' => 'Button Purple', 'title' => T(""))); ?></li>
            </ul>
         </div>
         <div class="About">
            <strong>We believe that online communities should be intuitive, engaging and true to your brand.</strong> Vanilla allows you to create acustomized community that rewards positive participation, automatically curates content and lets members drive moderation.
         </div>
      </div>
      <div class="Column">
         <h2>Vanilla Open Source</h2>
         <ul class="Features">
            <li><?php echo Sprite('SpCheck'); ?> Free Download</li>
            <li><?php echo Sprite('SpCheck'); ?> You Host It</li>
            <li><?php echo Sprite('SpCheck'); ?> You Optimize It</li>
            <li><?php echo Sprite('SpCheck'); ?> You Break It, You Fix It</li>
         </ul>
         <div class="Info">
            <h3>Latest Stable Download: <?php echo Anchor('Version '.$Version, 'download'); ?></h3>
            <p>Released: <?php echo Gdn_Format::Date($DateUploaded); ?></p>
            <p><?php echo Anchor('System Requirements', 'docs/installation-requirements'); ?></p>
         </div>
         <div class="About">
            Vanilla provides cloud and open source community forum software that powers discussion forums on <?php echo $Installtions; ?> sites. Built for flexibility and integration, <strong>Vanilla is the best, most powerful community solution in the world.</strong>
         </div>
      </div>
   </div>
</div>

<div class="Foot">
   <div class="Row">
      <label>Awesome companies use Vanilla's community forum software:</label>
      <strong>9to5 Mac, HubSpot, Corptax, O'Reilly Media, Boagworld, Car Talk, Penny Arcade, Mozilla, and plenty more.</strong>
   </div>
</div>
