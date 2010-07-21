<?php if (!defined('APPLICATION')) exit();
$TermsOfServiceUrl = Gdn::Config('Garden.TermsOfService', '#');
$TermsOfServiceText = sprintf(T('I agree to the <a id="TermsOfService" class="Popup" target="terms" href="%s">terms of service</a>'), Url($TermsOfServiceUrl));
$CaptchaPublicKey = Gdn::Config('Garden.Registration.CaptchaPublicKey');
?>
<div class="DownloadForm">
	<i class="Sprite SpriteTools"></i>
	<h1>
		Hobbyists
		<span>Download &amp; do-it-yourself</span>
	</h1>
	<?php
	echo $this->Form->Open();
	echo $this->Form->Errors();
	if (!Gdn::Session()->IsValid()) {
	?>
	<ul>
		<li>
			<?php
				echo $this->Form->Label('Email', 'Email');
				echo $this->Form->TextBox('Email');
			?>
		</li>
		<li>
			<?php
				echo $this->Form->CheckBox('Newsletter', 'Email me news about Vanilla Forums', array('value' => '1'));
			?>
		</li>
		<li>
			<?php
				echo $this->Form->CheckBox('CreateAccount', 'Join the VanillaForums.org developer community', array('value' => '1'));
			?>
		</li>
	</ul>
	<ul class="JoinFields">
      <li>
         <?php
            echo $this->Form->Label('Username', 'Name');
            echo $this->Form->TextBox('Name');
            echo '<span id="NameUnavailable" class="Incorrect" style="display: none;">'.T('Name Unavailable').'</span>';
         ?>
      </li>
      <li>
         <?php
            echo $this->Form->Label('Password', 'Password');
            echo $this->Form->Input('Password', 'password');
         ?>
      </li>
      <li>
         <?php
            echo $this->Form->Label('Confirm Password', 'PasswordMatch');
            echo $this->Form->Input('PasswordMatch', 'password');
            echo '<span id="PasswordsDontMatch" class="Incorrect" style="display: none;">'.T("Passwords don't match").'</span>';
         ?>
      </li>
      <li class="CaptchaInput"><?php
         echo $this->Form->Label("Security Check", '');
         echo recaptcha_get_html($CaptchaPublicKey);
      ?></li>
      <li>
         <?php
            echo $this->Form->CheckBox('TermsOfService', $TermsOfServiceText, array('value' => '1'));
            echo $this->Form->CheckBox('RememberMe', T('Remember me on this computer'), array('value' => '1'));
         ?>
      </li>
	</ul>
	<?php
	}
	echo $this->Form->Button('', array('type' => 'image', 'class' => '', 'src' => Asset('themes/vforg/design/images/btn-download.png')));
	echo $this->Form->Close();
	?>
</div>
<div class="HostingForm">
	<i class="Sprite SpriteSuit"></i>
	<h1>
		Businesses
		<span>Get help growing your community</span>
	</h1>
   <div class="Quote">
      <?php echo Img('/applications/vforg/design/images/icon-moody.png', array('alt' => 'Chris Moody')); ?>
      <p><strong>Vanilla is a great example of what happens when talented people take a fresh approach to an old problem.</strong></p>
      <p class="Author">- Chris Moody, <em>President, Aquent On Demand</em></p>
      <div class="Foot"></div>
   </div>

   <div class="Quote">
      <?php echo Img('/applications/vforg/design/images/icon-evan.png', array('alt' => 'Evan Prodromou')); ?>
      <p><strong>
			<!-- We were reluctant to launch a community forum because the software we knew was difficult to maintain and gave a very poor first impression. -->
			We've been delighted with our forum on vanillaforums.com; it set up almost instantly and has been a breeze to use and manage. Best of all, it makes us look good to our customers.
		</strong></p>
      <p class="Author">- Evan Prodromou, <em>CEO, Status.net</em></p>
      <div class="Foot"></div>
   </div>
	<?php echo Anchor(Wrap('<strong>See Plans &amp; Pricing</strong> 30-day Free Trial. Build your community in 60 seconds.'), 'http://vanillaforums.com/plans?ref=download', 'RenderedDownloadButton'); ?>
</div>
