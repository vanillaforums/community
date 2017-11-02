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
class VFOrgHooks extends Gdn_Plugin {

    /**
     * Remove mobile rendering from our custom non-forum pages.
     *
     * @param $sender
     */
    public function base_render_before($sender) {
        $userFacing = $sender->MasterView == 'default' || $sender->MasterView == '';
        $onForumPage = !in_array(strtolower($sender->Application), array('vanilla', 'conversations', 'dashboard'));
        if ($userFacing && isMobile() && $onForumPage) {
            // Use the main theme instead of mobile
            $sender->Theme = c('Garden.Theme');
            Gdn::pluginManager()->unregisterPlugin('MobileThemeHooks');
        }
    }

    /**
     * Add menu items to Dashboard.
     *
     * @param $sender
     */
    public function base_getAppSettingsMenuItems_handler($sender) {
        $Menu = $sender->EventArguments['SideMenu'];
        $Menu->addLink('Site Settings', 'Update Checkers', 'updates/', 'Garden.Settings.Manage');
        $Menu->addLink('Site Settings', 'Download Summary', 'vstats', 'Garden.Settings.Manage');
    }

    /**
     * Homepage of VanillaForums.org.
     *
     * @param Gdn_Controller $sender
     */
    public function homeController_homepage_create($sender) {
        try {
            $AddonModel = new AddonModel();
            $Addon = $AddonModel->getSlug('vanilla-core', true);
            $sender->setData('CountDownloads', val('CountDownloads', $Addon));
            $sender->setData('Version', val('Version', $Addon));
            $sender->setData('DateUploaded', val('DateInserted', $Addon));
        } catch (Exception $ex) {
        }
        $sender->title('The most powerful custom community solution in the world');
        $sender->setData('Description', "Vanilla is forum software that powers discussions on hundreds of thousands of sites. Built for flexibility and integration, Vanilla is the best, most powerful community solution in the world.");
        $sender->Head->addTag('meta', array('name' => 'description', 'content' => $sender->data('Description')));

        $sender->clearJsFiles();
        $sender->addJsFile('jquery.js', 'vforg');
        $sender->addJsFile('easySlider1.7.js', 'vforg');
        saveToConfig('Garden.Embed.Allow', false, false); // Prevent JS errors

        $sender->clearCssFiles();
        $sender->addCssFile('vforg-home.css', 'vforg');
        $sender->MasterView = 'empty';
        $sender->render('index', 'home', 'vforg');
    }

    /**
     * Woe unto those who wander here; abandon all logic and hope.
     *
     * @param Gdn_Controller $sender
     * @param string $Type
     * @param int $Length
     * @param string $FeedFormat
     */
    public function homeController_getFeed_create($sender, $Type = 'news', $Length = 5, $FeedFormat = 'normal') {
        $sender->MaxLength = is_numeric($Length) && $Length <= 50 ? $Length : 5;
        $sender->FeedFormat = $FeedFormat;
        switch ($Type) {
            case 'releases':
            case 'help':
                // Once you realize we're consuming our own local RSS you can never unsee it.
                $Url = 'http://vanillaforums.org/categories/blog/feed.rss';
                break;
            case 'news':
            case 'cloud':
            default:
                $Url = 'https://blog.vanillaforums.com/rss.xml';
        }

        $RawFeed = file_get_contents($Url);
        $sender->Feed = new SimpleXmlElement($RawFeed);
        $sender->render('getfeed', 'home', 'vforg');
    }

    /**
     * Splish splash I was takin' a... modal ad for cloud?
     *
     * @param Gdn_Controller $sender
     */
    public function homeController_splash_create($sender) {
        $sender->render('', false, 'vforg');
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
