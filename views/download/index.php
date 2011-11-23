<?php if (!defined('APPLICATION')) exit();
$TermsOfServiceUrl = Gdn::Config('Garden.TermsOfService', '#');
$TermsOfServiceText = sprintf(T('I agree to the <a id="TermsOfService" class="Popup" target="terms" href="%s">terms of service</a>'), Url($TermsOfServiceUrl));
$CaptchaPublicKey = Gdn::Config('Garden.Registration.CaptchaPublicKey');
?>
<div class="DownloadForm">
	<i class="Sprite SpriteTools"></i>
	<h1>
		Hobbyists
		<span>Download, install, configure, and optimize yourself</span>
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
		Big Communities
		<span>Get help hosting &amp; growing your community</span>
	</h1>
	<?php echo Anchor(Wrap('<strong>See Plans &amp; Pricing</strong> 30-day Free Trial. Take control of your community.'), 'http://vanillaforums.com/plans', 'RenderedDownloadButton'); ?>
</div>
