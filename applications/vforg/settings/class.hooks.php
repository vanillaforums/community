<?php
/**
 *
 *
 * @copyright 2009-2016 Vanilla Forums Inc.
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU GPL v2
 * @package VFOrg
 */

/**
 * Class VFOrgHooks
 */
class VFOrgHooks implements Gdn_IPlugin {

    /**
     * Remove mobile rendering from our custom non-forum pages.
     *
     * @param $Sender
     */
    public function base_render_before($Sender) {
        $userFacing = $Sender->MasterView == 'default' || $Sender->MasterView == '';
        $onForumPage = !in_array(strtolower($Sender->Application), array('vanilla', 'conversations', 'dashboard'));
        if ($userFacing && isMobile() && $onForumPage) {
            // Use the main theme instead of mobile
            $Sender->Theme = c('Garden.Theme');
            Gdn::pluginManager()->unRegisterPlugin('MobileThemeHooks');
        }
    }

    /**
     * Add menu items to Dashboard.
     *
     * @param $Sender
     */
    public function base_getAppSettingsMenuItems_handler($Sender) {
        $Menu = $Sender->EventArguments['SideMenu'];
        $Menu->addLink('Site Settings', 'Update Checkers', 'updates/', 'Garden.Settings.Manage');
        $Menu->addLink('Site Settings', 'Download Summary', 'vstats', 'Garden.Settings.Manage');
    }

    /**
     * Runs on enable.
     */
    public function setup() {
        $this->structure();


    }

    /**
     * Runs on /utility/update.
     */
    public function structure() {
        require_once('structure.php');
    }
}
