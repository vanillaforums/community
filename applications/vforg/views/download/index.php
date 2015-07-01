<?php if (!defined('APPLICATION')) exit();
$TermsOfServiceUrl = Gdn::Config('Garden.TermsOfService', '#');
$TermsOfServiceText = sprintf(T('I agree to the <a id="TermsOfService" class="Popup" target="terms" href="%s">terms of service</a>'), Url($TermsOfServiceUrl));
$CaptchaPublicKey = Gdn::Config('Garden.Registration.CaptchaPublicKey');
?>
<div class="DownloadForm">
     <i class="Sprite SpriteTools"></i>
    <div class="FooWrap">
        <h1>Developers</h1>
        <p>Download, install, configure, and optimize yourself</p>
    </div>
     <style>
          .hs-form .hs-input {
             -moz-box-sizing: content-box;
          }

          .hs-form .hs-button,
          .hs-form .hs-button:hover,
          .hs-form .hs-button:focus,
          .hs-form .hs-button:active {
                display: block;
                width: 370px;
                height: 59px;
                padding: 0;
                font: 0/0 a;
                color: transparent;
                text-shadow: none;
                background-color: transparent;
                background-image: url("<?php echo Url('/themes/vforg/design/images/btn-download.png') ?>");
                background-repeat: no-repeat;
                border: 0;
                box-shadow: none;
          }
     </style>
    <script charset="utf-8" src="//js.hsforms.net/forms/current.js"></script>
     <script>
     hbspt.forms.create({
     portalId: '95135',
     formId: '8fe5349a-e5bc-403c-9962-ba3bd688f9c0'
     });
     </script>
</div>
<div class="HostingForm">
     <i class="Sprite SpriteSuit"></i>
    <div class="FooWrap">
        <h1>Big Communities</h1>
        <p>Get help hosting &amp; growing your community</p>
    </div>
     <?php echo Anchor(Wrap('<strong>See Plans &amp; Pricing</strong> 30-day Free Trial. Take control of your community.'), 'http://vanillaforums.com/plans', 'RenderedDownloadButton'); ?>
</div>
