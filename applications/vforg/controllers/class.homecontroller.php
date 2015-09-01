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
      *
      */
    public function Initialize() {
        parent::Initialize();

        try {
            $AddonModel = new AddonModel();
            $Addon = $AddonModel->GetSlug('vanilla-core', TRUE);

            $this->SetData('CountDownloads', $Addon ? $Addon['CountDownloads'] : 350000);
            $this->SetData('Version', $Addon ? $Addon['Version'] : '2.0');
            $this->SetData('DateUploaded', $Addon ? $Addon['DateInserted'] : '2010-07-21 00:00:00');

            $CountDownloads = $this->Data('CountDownloads', 0);
            if ($CountDownloads < 500000)
                $CountDownloads = 500000;
            $CountDownloads = number_format($CountDownloads);
        } catch (Exception $ex) {
        }
        $this->Title('The most powerful custom community solution in the world');
        $this->SetData('Description', "Vanilla is forum software that powers discussions on $CountDownloads sites. Built for flexibility and integration, Vanilla is the best, most powerful community solution in the world.");
        $this->Head->AddTag('meta', array('name' => 'description', 'content' => $this->Data('Description')));
    }


    public function Index() {
        $this->ClearJsFiles();
        $this->AddJsFile('jquery.js');
        $this->AddJsFile('easySlider1.7.js');
        $Options = array('Save' => FALSE);
        SaveToConfig('Garden.Embed.Allow', FALSE, $Options); // Prevent JS errors
        try {
            $AddonModel = new AddonModel();
            $Addon = $AddonModel->GetSlug('vanilla-core', TRUE);
            $this->SetData('CountDownloads', $Addon ? $Addon['CountDownloads'] : 350000);
            $this->SetData('Version', $Addon ? $Addon['Version'] : '2.0');
            $this->SetData('DateUploaded', $Addon ? $Addon['DateInserted'] : '2010-07-21 00:00:00');
        } catch (Exception $ex) {
            // Do nothing
        }

        $this->ClearCssFiles();
        //$this->AddCssFile('splash.css');
        $this->AddCssFile('vforg-home.css');
        $this->MasterView = 'empty';
        $this->Render();
        die();
    }

    public function Hosting() {
        Redirect('http://vanillaforums.com', 301);
    }

    public function Features() {
        Redirect('http://vanillaforums.com/features', 301);
    }

    public function Get() {
        $this->Render();
    }

    public function Download() {
        Redirect('download');
    }

    public function Services($Service = '') {
        Redirect('http://vanillaforums.com', 301);
    }


    public function GetFeed($Type = 'news', $Length = 5, $FeedFormat = 'normal') {
        $this->MaxLength = is_numeric($Length) && $Length <= 50 ? $Length : 5;
        $this->FeedFormat = $FeedFormat;
        switch ($Type) {
            case 'releases':
            case 'help':
                $Url = 'http://vanillaforums.org/categories/blog/feed.rss';
                break;
            case 'news':
            case 'cloud':
            default:
                $Url = 'http://blog.vanillaforums.com/feed/';
        }

        $RawFeed = file_get_contents($Url);
        $this->Feed = new SimpleXmlElement($RawFeed);
        $this->Render();
    }

    public function ProxyFeed($Url) {
        $Key = 'Feed|'.$Url;
        $Feed = Gdn::Cache()->Get($Key);
        if (!$Feed) {
            $Feed = ProxyRequest($Url, 5);
            Gdn::Cache()->Store($Key, $Feed, array(Gdn_Cache::FEATURE_EXPIRY => 5 * 60));
        }
        return $Feed;
    }

    public function Splash() {
        $this->Render();
    }

}