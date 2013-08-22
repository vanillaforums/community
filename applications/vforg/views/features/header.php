<?php
function m($Sender, $Url, $Text) {
  echo '<a';
  if ($Sender->View == $Url)
	 echo ' class="Active"';
  
  echo ' href="'.Url('/features/'.$Url).'">'.$Text.'</a>';
}
?>
<div class="Features">
  <h1>Vanilla Forums Features</h1>
  <p>Here are just a few killer features Vanilla Forums have to offer.</p>

  <div id="FeatureList">
	 <?php
	 m($this, 'embed-vanilla', '&lt;embed&gt; Vanilla');
	 m($this, 'vanilla-api', 'Vanilla API');
	 m($this, 'social-connect', 'Social Connect');
	 m($this, 'mobile', 'Vanilla Mobile');
	 m($this, 'vanilla-connect', 'Vanilla Connect');
	 m($this, 'themes', 'Themes');
	 m($this, 'banner', 'Banner');
	 m($this, 'file-upload', 'File Upload');
	 m($this, 'import-tool', 'Import Tool');
	 ?>
  </div>
</div>