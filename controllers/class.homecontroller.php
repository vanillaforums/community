<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2008, 2009 Vanilla Forums Inc.
This file is part of Garden.
Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
Contact Vanilla Forums Inc. at support [at] vanillaforums [dot] com
*/

class HomeController extends VFOrgController {
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
      $this->SetData('Description', "Vanilla is forum software that powers discussions on over $CountDownloads sites. Built for flexibility and integration, Vanilla is the best, most powerful community solution in the world.");
      $this->Head->AddTag('meta', array('name' => 'description', 'content' => $this->Data('Description')));
   }
   
   
   public function Index() {
      // If the user does not have an active session, or they have not yet viewed the "chooser", redirect them to it.
      /*
      $Session = Gdn::Session();
      $ViewedChooser = ArrayValue('VanillaViewedChooser', $_COOKIE);
      if (!$Session->IsValid() && !$ViewedChooser)
         Redirect('/choose');
      */
      
      $this->AddJsFile('jquery.js');
      $this->AddJsFile('jquery.livequery.js');
      $this->AddJsFile('global.js');
      $this->AddJsFile('home.js');
      $this->AddJsFile('easySlider1.7.js');
      $this->AddCssFile('splash.css');
      try {
         $AddonModel = new AddonModel();
         $Addon = $AddonModel->GetSlug('vanilla-core', TRUE);
         
         $this->SetData('CountDownloads', $Addon ? $Addon['CountDownloads'] : 350000);
         $this->SetData('Version', $Addon ? $Addon['Version'] : '2.0');
         $this->SetData('DateUploaded', $Addon ? $Addon['DateInserted'] : '2010-07-21 00:00:00');

/*         
         $NewsFeed = $this->ProxyFeed(Url('/vforg/home/getfeed/blog?DeliveryType=VIEW', TRUE));
         $this->SetData('NewsFeed', $NewsFeed);

         $EventsFeed = $this->ProxyFeed(Url('/vforg/home/getfeed/events?DeliveryType=VIEW', TRUE));
         $this->Data('EventsFeed', $EventsFeed);
*/
      } catch (Exception $ex) {
         // Do nothing
      }
      
      $this->Render();
      die();
   }
   
   public function Hosting() {
      Redirect('http://vanillaforums.com', 301);
      // $this->Render();
      
   }
   
   public function Features() {
      Redirect('http://vanillaforums.com/features', 301);
      /*
      $this->Head->AddScript('http://vanillaforums.com/applications/vfcom/js/cufon-yui.js');
      $this->Head->AddScript('http://vanillaforums.com/applications/vfcom/js/archer.font.js');
      $this->Head->AddScript('http://vanillaforums.com/applications/vfcom/js/gothamround.font.js');
      $this->Head->AddString("
<script type=\"text/javascript\">
   Cufon.replace('#Features h2, #Features h4', {
      fontFamily: 'Archer',
      textShadow: '0px 1px 1px #ffffff;'
   });
</script>");      
      $this->Render();
      */
   }
   
   public function Get() {
      $this->Render();
   }
   
   public function Download() {
      Redirect('download');
   }
   
   public function Services($Service = '') {
      Redirect('http://vanillaforums.com', 301);
      /*
      if ($Service != '' && in_array($Service, array('installation', 'consultation', 'support'))) {
         $this->View = $Service;
      } else {
         $this->AddJsFile('jquery.js');
         $this->AddJsFile('jquery.popup.js');
         $this->AddJsFile('jquery.livequery.js');
         $this->AddJsFile('global.js');
         $this->Head->AddScript('http://vanillaforums.com/applications/vfcom/js/cufon-yui.js');
         $this->Head->AddScript('http://vanillaforums.com/applications/vfcom/js/archer.font.js');
         $this->Head->AddScript('http://vanillaforums.com/applications/vfcom/js/gothamround.font.js');
         $this->Head->AddString("
<script type=\"text/javascript\">
   Cufon.replace('.Services h1', {
      fontFamily: 'Archer',
      textShadow: '0px 1px 1px #ffffff;'
   });
</script>");
      }

      $this->Render();
      */
   }
   
   
   public function GetFeed($Type = 'news', $Length = 5, $FeedFormat = 'normal') {
      $this->MaxLength = is_numeric($Length) && $Length <= 50 ? $Length : 5;
      $this->FeedFormat = $FeedFormat;
      switch ($Type) {
         case 'events':
            $Url = 'http://vanillaforums.com/blog/category/events/feed/';
            break;
         case 'help':
            $Url = 'http://vanillaforums.com/blog/category/help/feed/';
            break;
         default:
            $Url = 'http://vanillaforums.com/blog/category/news/feed/';
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
//      } else {
//         $Feed = 'Cached'.$Feed;
      }
      return $Feed;
   }
   
   public function Splash() {
      /*
      $this->MasterView = 'splash';
      $this->ClearCssFiles();
      $this->AddCssFile('splash.css');
      */
      $this->Render();
   }

/*
   public function Choose() {
      $this->Title('The most powerful custom community solution in the world - Vanilla Forums');
      setcookie('VanillaViewedChooser', 'TRUE', time()+60*60*24*300, C('Garden.Cookie.Path'), C('Garden.Cookie.Domain')); // Expire in 300 days
      $this->MasterView = 'empty';
      $this->ClearCssFiles();
      $this->AddCssFile('choose.css');
      $this->Render();
   }
*/
}