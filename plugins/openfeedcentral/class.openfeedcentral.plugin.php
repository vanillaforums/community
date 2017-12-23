<?php
/**
 * Open Feed Central Plugin.
 *
 * @copyright 2009-2017 Vanilla Forums Inc.
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU GPL v2
 */

/**
 * Class OpenFeedCentralPlugin
 */
class OpenFeedCentralPlugin extends Gdn_Plugin {

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
                $Url = 'https://open.vanillaforums.com/categories/blog/feed.rss';
                break;
            case 'news':
            case 'cloud':
            default:
                $Url = 'https://blog.vanillaforums.com/rss.xml';
        }

        $RawFeed = file_get_contents($Url);
        $sender->Feed = new SimpleXmlElement($RawFeed);
        $sender->render('getfeed', 'home', 'plugins/openfeedcentral');
    }

}