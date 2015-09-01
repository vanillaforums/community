<?php if (!defined('APPLICATION')) exit();

$this->Title(T("Community Forum Software"));

$Version        = GetValue('Version', $this->Data, '2.0');
$DateUploaded   = GetValue('DateUploaded', $this->Data, '2010-07-21 00:00:00');
$CountDownloads = GetValue('CountDownloads', $this->Data);

function Alt($Alt) {
    return array('alt' => $Alt, 'title' => $Alt);
}
?>

<div class="Head">
    <div class="Row">
        <?php
        echo Anchor(T("Vanilla Forums: Community Forums Evolved"), '/', array('class' => 'Home'));
        echo Anchor(T('Vanilla for Business <br /> <span class="SmallText14">Free Trial</span>'), 'https://accounts.vanillaforums.com/signup/advanced', array('class' => 'Button Pink Trial', 'title' => T("Use Vanilla Forums in the Cloud")));
        echo '<div class="Menu">';
            echo Anchor(T("Pricing"), 'https://vanillaforums.com/plans', array('title' => T("Pricing for Vanilla")));
            echo Anchor(T("Addons"), 'addons', array('title' => T("Themes, plugins and applications for Vanilla")));
            echo Anchor(T("Community"), 'discussions', array('title' => T("Vanilla Forums Developer Community")));
            echo Anchor(T("Documentation"), '//docs.vanillaforums.com', array('title' => T("Developer documentation for Vanilla")));
            echo Anchor(T("Blog"), 'http://vanillaforums.com/blog', array('title' => T("Latest news from the Vanilla Team")));
        echo '</div>';

        ?>
    </div>
</div>

<div class="Row ContentRow">
    <div class="Row HeroRow">
        <h1>Community Forums Evolved.</h1>
        <div class="HeroImg"><?php echo Img('applications/vforg/design/images/vforg_hero.png', Alt(T("Community Forums Evolved"))); ?></div>
    </div>

    <div class="Columns">
        <div class="Column BusinessColumn">
            <div class="BusinessFeatures">
                <h1>Vanilla for Business</h1>
                <div class="About">
                    A robust cloud-based community forum solution. Used by many of the world’s leading brands.
                </div>
                <ul class="Features">
                    <li><i class="fa fa-check fa-2x"></i> Packed with premium features</li>
                    <li><i class="fa fa-check fa-2x"></i> Scalable and secure</li>
                    <li><i class="fa fa-check fa-2x"></i> Cloud-based</li>
                    <li><i class="fa fa-check fa-2x"></i> Fully supported </li>
                    <li><i class="fa fa-check fa-2x"></i> Professional services</li>
                </ul>
            </div>

            <div id="DivBusinessButtonTextTop" onclick="document.getElementById('hsForm_24bbd6e1-9f3b-4c09-92a5-8944e4c4c1d8_topFormBusiness').submit();">
                Try Vanilla for Business <br> <span class="SmallText12">for 30 Days Free</span>
            </div>
            <i class="fa fa-rocket fa-5x TopRocket" onclick="document.getElementById('hsForm_24bbd6e1-9f3b-4c09-92a5-8944e4c4c1d8_topFormBusiness').submit();"></i>

            <div class="SignUpBoxBusiness">
                <!--[if lte IE 8]>
                <script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2-legacy.js"></script>
                <![endif]-->
                <script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2.js"></script>
                <script>
                    hbspt.forms.create({
                        sfdcCampaignId: '701U00000007qs3IAA',
                        portalId: '95135',
                        formInstanceId: 'topFormBusiness',
                        formId: '24bbd6e1-9f3b-4c09-92a5-8944e4c4c1d8'
                    });
                </script>
                <!--
                <div class="Input">
                    <input type="text" class="BusinessInput" placeholder="Enter email...">
                </div>
                <div class="Info">
                    <i class="fa fa-rocket fa-3x TopRocket"></i><?php// echo Anchor('Try Vanilla for Business <br> <span class="SmallText12">for 30 Days Free</span>', 'http://vanillaforums.com/plans', array('class' => 'BigButton Blue', 'title' => T("Sign up for a 30-day free trial of Vanilla Cloud"))); ?>
                </div>
                -->
            </div>
        </div>
        <div class="Column OpenSourceColumn">
            <div class="OpenSourceFeatures">
                <h1>Vanilla Open Source</h1>
                <div class="About">
                    Simple and flexible forum software.
                    Great for enthusiasts and small businesses.
                </div>
                <ul class="Features">
                    <li><i class="fa fa-check fa-2x"></i> Free Download</li>
                    <li><i class="fa fa-check fa-2x"></i> Host it yourself</li>
                    <li><i class="fa fa-check fa-2x"></i> You Break It, You Fix It</li>
                    <li><i class="fa fa-check fa-2x"></i> Community supported</li>
                </ul>
            </div>

            <div id="DivOpenSourceButtonTextTop" onclick="document.getElementById('hsForm_b943a8b8-0b98-4360-a019-18c79f01fa55_topFormOpenSource').submit();">
                Download Vanilla Open Source
            </div>
            <i class="fa fa-download fa-3x TopOSSDownload" onclick="document.getElementById('hsForm_b943a8b8-0b98-4360-a019-18c79f01fa55_topFormOpenSource').submit();"></i>

            <div class="SignUpBoxOpenSource">
                <!--[if lte IE 8]>
                <script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2-legacy.js"></script>
                <![endif]-->
                <script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2.js"></script>
                <script>
                    hbspt.forms.create({
                        sfdcCampaignId: '701U00000007qs3IAA',
                        portalId: '95135',
                        formInstanceId: 'topFormOpenSource',
                        formId: 'b943a8b8-0b98-4360-a019-18c79f01fa55'
                    });
                </script>
            </div>
        </div>
    </div>
</div>

<div class="Row BottomRow">
    <h1>What You Get</h1>

    <div class="Columns WhatYouGet">
        <div class="Column BottomBusinessColumn">
            <div class="BusinessFeaturesWrapper">
                <ul class="Features">
                    <li><i class="fa fa-check fa-2x"></i> Best in class uptime and page load speeds. </li>
                    <li><i class="fa fa-check fa-2x"></i> Secure and updated. </li>
                    <li><i class="fa fa-check fa-2x"></i> Premium features such as gamification<br><span class="SpanMarginRight"></span> and CRM integrations. </li>
                    <li><i class="fa fa-check fa-2x"></i> Support for multiple SSO protocols. </li>
                    <li><i class="fa fa-check fa-2x"></i> Advanced analytics and API support. </li>
                    <li><i class="fa fa-check fa-2x"></i> Data migration, theming and integration services. </li>
                    <li><i class="fa fa-check fa-2x"></i> Vanilla’s support and professional services teams<br><span class="SpanMarginRight"></span> to help you achieve your goals. </li>
                </ul>
            </div>

            <div id="DivBusinessButtonTextBottom" onclick="document.getElementById('hsForm_24bbd6e1-9f3b-4c09-92a5-8944e4c4c1d8_bottomFormBusiness').submit();">
                Try Vanilla for Business <br> <span class="SmallText12">for 30 Days Free</span>
            </div>
            <i class="fa fa-rocket fa-5x BottomRocket" onclick="document.getElementById('hsForm_24bbd6e1-9f3b-4c09-92a5-8944e4c4c1d8_bottomFormBusiness').submit();"></i>

            <div class="SignUpBoxBusiness">
                <!--[if lte IE 8]>
                <script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2-legacy.js"></script>
                <![endif]-->
                <script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2.js"></script>
                <script>
                    hbspt.forms.create({
                        sfdcCampaignId: '701U00000007qs3IAA',
                        portalId: '95135',
                        formInstanceId: 'bottomFormBusiness',
                        formId: '24bbd6e1-9f3b-4c09-92a5-8944e4c4c1d8'
                    });

                </script>
                <div class="Text">
                    <strong>We believe that online communities should be intuitive, engaging and true to your brand.</strong>
                    Vanilla allows you to create a customized community that rewards positive participation, automatically curates content and lets members drive moderation.
                </div>
            </div>
        </div>
        <div class="Column BottomOpenSourceColumn">
            <div class="OpenSourceFeaturesWrapper">
                <ul class="Features">
                    <li><i class="fa fa-check fa-2x"></i> A forum application that is easy to install <br><span class="SpanMarginRight"></span> and manage. </li>
                    <li><i class="fa fa-check fa-2x"></i> Community contributed plugins. </li>
                    <li><i class="fa fa-check fa-2x"></i> Help via the community forum. </li>
                </ul>
            </div>

            <div id="DivOpenSourceButtonTextBottom" onclick="document.getElementById('hsForm_b943a8b8-0b98-4360-a019-18c79f01fa55_bottomFormOpenSource').submit();">
                Download Vanilla Open Source
            </div>
            <i class="fa fa-download fa-3x BottomOSSDownload" onclick="document.getElementById('hsForm_b943a8b8-0b98-4360-a019-18c79f01fa55_bottomFormOpenSource').submit();"></i>

            <div class="SignUpBoxOpenSource">
                <!--[if lte IE 8]>
                <script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2-legacy.js"></script>
                <![endif]-->
                <script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2.js"></script>
                <script>
                    hbspt.forms.create({
                        sfdcCampaignId: '701U00000007qs3IAA',
                        portalId: '95135',
                        formInstanceId: 'bottomFormOpenSource',
                        formId: 'b943a8b8-0b98-4360-a019-18c79f01fa55'
                    });
                </script>

                <div class="Text">
                    Vanilla provides cloud and open source community forum software that powers discussion forums on 823,234 sites.
                    Built for flexibility and integration, <strong>Vanilla is the best, most powerful community solution in the world.</strong>
                </div>
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
