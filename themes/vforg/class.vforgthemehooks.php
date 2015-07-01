<?php
/**
 *
 *
 * @copyright 2009-2015 Vanilla Forums Inc.
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU GPL v2
 * @package VFOrgTheme
 * @since 2.0
 */

/**
 * Class VFOrgThemeHooks
 */
class VFOrgThemeHooks implements Gdn_IPlugin {

    public function Setup() {
        return TRUE;
    }

    /**
     * Output random honeypot URL
     *
     * @param Gdn_Controller $sender
     */
    public function Base_Render_Before($sender) {
        $opts = [
            '<a href="http://www.vanillaforums.org/cgi-bin/badger.php"><!-- similarity-standard --></a>',
            '<a href="http://www.vanillaforums.org/cgi-bin/badger.php"><img src="similarity-standard.gif" height="1" width="1" border="0"></a>',
            '<a href="http://www.vanillaforums.org/cgi-bin/badger.php" style="display: none;">similarity-standard</a>',
            '<div style="display: none;"><a href="http://www.vanillaforums.org/cgi-bin/badger.php">similarity-standard</a></div>',
            '<a href="http://www.vanillaforums.org/cgi-bin/badger.php"></a>',
            '<!-- <a href="http://www.vanillaforums.org/cgi-bin/badger.php">similarity-standard</a> -->',
            '<div style="position: absolute; top: -250px; left: -250px;"><a href="http://www.vanillaforums.org/cgi-bin/badger.php">similarity-standard</a></div>',
            '<a href="http://www.vanillaforums.org/cgi-bin/badger.php"><span style="display: none;">similarity-standard</span></a>',
            '<a href="http://www.vanillaforums.org/cgi-bin/badger.php"><div style="height: 0px; width: 0px;"></div></a>'
        ];

        $key = array_rand($opts, 1);
        $link = $opts[$key];
        $sender->AddAsset('Foot', $link, 'Badger');
    }

    /**
     *
     * @param PostController $Sender
     */
    public function PostController_Render_Before($Sender) {
        $Sender->Head->AddString("<script type=\"text/javascript\">
jQuery(document).ready(function($) {
   $('.HelpFormat, .HelpTags').hide();
   $('#Form_Name').focus(function() { $('.Help').hide(); $('.HelpTitle').show(); });
   $('#Form_Body').focus(function() { $('.Help').hide(); $('.HelpFormat').show(); });
   $('#Form_Tags').focus(function() { $('.Help').hide(); $('.HelpTags').show(); });
});
</script>");
    }
}
