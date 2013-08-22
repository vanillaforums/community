<?php if (!defined('APPLICATION')) exit();

function WriteUserLanguage($UserLanguage, $Alt, $CountTranslations) {
	$Session = Gdn::Session();
	$Url = '/translations/view/'.$UserLanguage->UserLanguageID.'/';
	?>
	<li class="UserLanguageRow<?php echo $Alt; ?>">
		<h3><?php
			echo Anchor($UserLanguage->Name.' ('.$UserLanguage->Code.')', $Url);
		?> by <?php
			$User = new stdClass();
			$User->Name = $UserLanguage->InsertName;
			$User->UserID = $UserLanguage->InsertUserID;
			echo UserAnchor($User);
		?></h3>
		<ul class="Options">
			<li><?php echo Anchor('Contribute', $Url); ?></li>
			<li><?php echo Anchor('Fork', $Url); ?></li>
			<li><?php
				$Text = 'Like';
				if ($UserLanguage->CountLikes > 0)
					$Text .= '<span>'.$UserLanguage->CountLikes.'</span>';
					
				echo Anchor($Text, '/translations/like/'.$UserLanguage->UserLanguageID.'/'.$Session->TransientKey());
			?></li>
			<li><?php
				$Text = 'Download';
				if ($UserLanguage->CountDownloads > 0)
					$Text .= '<span>'.$UserLanguage->CountDownloads.'</span>';
					
				echo Anchor($Text, '/translations/get/'.$UserLanguage->UserLanguageID);
			?></li>
			<li><?php
				if ($CountTranslations <= 0)
					$CountTranslations = 1;
					
				$PercentComplete = round(($UserLanguage->CountTranslations * 100) / $CountTranslations, 0);
				echo $PercentComplete . '% Complete';
			?></li>
		</ul>
	</li>
<?php
}