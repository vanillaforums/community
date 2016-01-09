<?php
/**
 *
 *
 * @copyright 2009-2015 Vanilla Forums Inc.
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU GPL v2
 * @package Addons
 * @since 2.0
 */

/**
 * Class HomeController
 */
class HomeController extends VFOrgController {

     /**
      * Before all other method calls.
      */
    public function initialize() {
        parent::initialize();
        try {
            $AddonModel = new AddonModel();
            $Addon = $AddonModel->getSlug('vanilla-core', true);
            $this->setData('CountDownloads', val('CountDownloads', $Addon));
            $this->setData('Version', val('Version', $Addon));
            $this->setData('DateUploaded', val('DateInserted', $Addon));
        } catch (Exception $ex) {
        }
        $this->title('The most powerful custom community solution in the world');
        $this->setData('Description', "Vanilla is forum software that powers discussions on hundreds of thousands of sites. Built for flexibility and integration, Vanilla is the best, most powerful community solution in the world.");
        $this->Head->addTag('meta', array('name' => 'description', 'content' => $this->Data('Description')));
    }

    /**
     * Homepage of VanillaForums.org.
     */
    public function index() {
        $this->clearJsFiles();
        $this->addJsFile('jquery.js');
        $this->addJsFile('easySlider1.7.js');
        saveToConfig('Garden.Embed.Allow', false, false); // Prevent JS errors

        $this->clearCssFiles();
        $this->addCssFile('vforg-home.css');
        $this->MasterView = 'empty';
        $this->render();
    }

    /**
     * Woe unto those who wander here; abandon all logic and hope.
     *
     * @param string $Type
     * @param int $Length
     * @param string $FeedFormat
     */
    public function getFeed($Type = 'news', $Length = 5, $FeedFormat = 'normal') {
        $this->MaxLength = is_numeric($Length) && $Length <= 50 ? $Length : 5;
        $this->FeedFormat = $FeedFormat;
        switch ($Type) {
            case 'releases':
            case 'help':
                // Once you realize we're consuming our own local RSS you can never unsee it.
                $Url = 'http://vanillaforums.org/categories/blog/feed.rss';
                break;
            case 'news':
            case 'cloud':
            default:
                $Url = 'http://blog.vanillaforums.com/feed/';
        }

        $RawFeed = file_get_contents($Url);
        $this->Feed = new SimpleXmlElement($RawFeed);
        $this->render();
    }

    /**
     * Proxy an RSS feed for Dashboard use across our kingdom.
     *
     * @param $Url
     * @return mixed|string
     * @throws Exception
     */
    public function proxyFeed($Url) {
        $Key = 'Feed|'.$Url;
        $Feed = Gdn::cache()->get($Key);
        if (!$Feed) {
            $Feed = ProxyRequest($Url, 5);
            Gdn::cache()->store($Key, $Feed, array(Gdn_Cache::FEATURE_EXPIRY => 5 * 60));
        }
        return $Feed;
    }

    /**
     * Splish splash I was takin' a... modal ad for cloud?
     */
    public function splash() {
        $this->render();
    }
}