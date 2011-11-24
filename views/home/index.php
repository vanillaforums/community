<?php if (!defined('APPLICATION')) exit();
$this->Title('The most powerful custom community solution in the world');
?>
<script type="text/javascript">
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
<div class="Content Wrapper">
   <div class="Actions">
      <?php
      $Version = GetValue('Version', $this->Data, '2.0');
      $DateUploaded = GetValue('DateUploaded', $this->Data, '2010-07-21 00:00:00');
      $CountDownloads = GetValue('CountDownloads', $this->Data);
      
      // echo Anchor('<strong>Get Your Vanilla Forum Now</strong> Vanilla '.$Version.' - Released '.Gdn_Format::Date($DateUploaded), 'plans', 'GreenRibbon Plans');
      // echo Anchor('<strong>See Plans and Pricing to Get Started</strong> 30-day free trial. <span>YES, there is a free plan!</span>', 'plans', 'GreenRibbon Plans');

      echo Anchor("<strong>Get Started Now!</strong> Plans &amp; Pricing", 'http://vanillaforums.com/plans', 'CredRibbon Plans');
      
      echo '<div class="Action">';

      echo Anchor('<strong>Host With Us!</strong> On our Fully Scalable Infrastructure', 'http://vanillaforums.com', 'GreenButton HostButton');
      echo Wrap('or');
      echo Anchor('<strong>Download Vanilla</strong> Vanilla '.$Version.' - Released '.Gdn_Format::Date($DateUploaded), 'download', 'OrangeButton DownloadButton');
      echo '</div>';
      ?>
   </div>
   <div class="Splashes">
      <div id="Slider">
         <ul>
            <li>
               <div class="Splash Splash1">
                  <div class="Feature">
                     <?php echo Img('applications/vfcom/design/images/feature-pennyarcade.png'); ?>
                  </div>
                  <h1>Custom Community Solutions</h1>
                  <p>Vanilla is forum software that powers discussions on over <?php
                     if (is_numeric($CountDownloads) && $CountDownloads > 500000)
                        echo number_format($CountDownloads);
                     else
                        echo '500,000';
                     ?> sites. Built for flexibility and integration, Vanilla is the best, most powerful community solution in the world.</p>
						<p>Vanilla can integrate seamlessly with your existing infrastructure to provide you with a completely custom community discussion & support platform for your stack.</p>
                  <p><?php echo Anchor('Learn More About Our Solutions', 'http://vanillaforums.com/solutions', 'BlueButton'); ?></p>
               </div>
            </li>
            <li>
               <div class="Splash Splash2">
                  <div class="Feature">
                     <?php echo Img('applications/vfcom/design/images/feature-community-analytics.png'); ?>
                  </div>
                  <h2>Community-Specific Analytics</h2>
                  <p>We give you the data that Google Analytics just doesn't have. Get a deep understanding of the activity on your community with breakdowns between guests, members, and moderators.</p>
						<p>Calculate ROI by understanding how many questions are answered by your community members vs your staff.</p>
                  <p><?php echo Anchor('Learn More About our Social CRM Solution', 'http://vanillaforums.com/solutions/customer-support-forum', 'BlueButton'); ?></p>
               </div>
            </li>
            <li>
               <div class="Splash Splash3">
                  <div class="Feature">
                     <?php echo Img('applications/vfcom/design/images/features-social-mobile.png'); ?>
                  </div>
                  <h2>Works Everywhere!</h2>
						<p>Vanilla works naturally on any device from computer to iPad to phone and has deep integrations with Facebook, Twitter, Google and any other social network you can throw our way.</p>
                  <p>VanillaForums.com provides a complete notification and email solution so discussions can happen naturally whether on the community or sitting in your inbox.</p>
                  <p><a class="BlueButton" href="http://vanillaforums.com/features">Learn More About Our Unparalleled Features</a></p>
               </div>
            </li>
         </ul>
      </div>
   </div>
</div>
<div class="SplashNav"></div>
<div class="Cred">
   Awesome companies use Vanilla to power their communities:
   <strong>9to5 Mac, HubSpot, Corptax, O'Reilly Media, Boagworld, Car Talk, Penny Arcade, Mozilla, and plenty more.</strong>
</div>