<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2008, 2009 Vanilla Forums Inc.
This file is part of Garden.
Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
Contact Vanilla Forums Inc. at support [at] vanillaforums [dot] com
*/


class VFOrgHooks implements Gdn_IPlugin {
   public function Base_Render_Before($Sender) {
      // If on user-facing pages
      if ($Sender->MasterView == 'default' || $Sender->MasterView == '') {
         // and in a mobile browser
         if (IsMobile()) {
            // and not on forum, inbox, profile pages
            if (!in_array(strtolower($Sender->Application), array('vanilla', 'conversations', 'dashboard'))) {
               // use the main theme instead of mobile
               $Sender->Theme = C('Garden.Theme');
               Gdn::PluginManager()->UnRegisterPlugin('MobileThemeHooks');
               /*
               $ThemeHooks = PATH_THEMES . DS . $Sender->Theme . DS . 'class.' . strtolower($Sender->Theme) . 'themehooks.php';
               if (file_exists($ThemeHooks)) {
               	include_once($ThemeHooks);
                  $ThemeInfo = array();
                  $ThemeInfo['VanillaForums.org'] = array(
                     'Name' => 'VanillaForums.org',
                     'Description' => "A custom theme for VanillaForums.org. Used to customize the heading banner for the forum.",
                     'Version' => '1.0.1',
                     'Author' => "Mark O'Sullivan",
                     'AuthorEmail' => 'mark@vanillaforums.com',
                     'AuthorUrl' => 'http://markosullivan.ca'
                  );
                  Gdn::PluginManager()->RegisterPlugin('VFOrgThemeHooks', $ThemeInfo);
               }
               */
            }
         }
      }
   }
   
   private function _UpdateCampaignMonitor($Email, $SubscriberName, $Newsletter) {
      // Update the newsletter record at campaign monitor
      if ($Email != '') {
         try {
            require_once(PATH_APPLICATIONS . DS . 'vforg' . DS . 'vendors' . DS . 'campaignmonitor-php-1.4.5' . DS . 'CMBase.php');
            //Your API Key. Go to http://www.campaignmonitor.com/api/required/ to see where to find this and other required keys
            $ApiKey = C('CampaignMonitor.ApiKey', NULL);
            $ClientID = NULL;
            $CampaignID = NULL;
            $ListID = C('CampaignMonitor.ListID', NULL);
            $CM = new CampaignMonitor($ApiKey, $ClientID, $CampaignID, $ListID);
            //Optional statement to include debugging information in the result
            //$cm->debug_level = 1;
            //This is the actual call to the method, passing email address, name.
            if ($Newsletter == '0' || !$Newsletter) {
               $result = $CM->subscriberUnsubscribe($Email);
            } else {
               $result = $CM->subscriberAdd($Email, $SubscriberName);
            }
//               Fail Quietly:
/*
               if($result['Result']['Code'] == 0)
                  echo 'Success';
               else
                  echo 'Error : ' . $result['Result']['Message'];
*/
         } catch (Exception $Ex) {
            // Do nothing with the exception (fail quietly)
         }
      }
   }
   
   // Add the newsletter checkbox to the edit account form
   public function ProfileController_EditMyAccountAfter_Handler($Sender) {
      echo Wrap(
         $Sender->Form->Label('Newsletter', 'Newsletter')
         .$Sender->Form->CheckBox('Newsletter', T('Subscribe to the VanillaForums.org Newsletter'), array('value' => '1'))
      , 'li');
   }
   
   // Update campaignmonitor after a user is saved.
   public function UserModel_AfterSave_Handler($Sender) {
      $UserID = GetValue('UserID', $Sender->EventArguments);
      if ($UserID) {
         $Data = $Sender->SQL->Select('Email, Name, Newsletter')->From('User')->Where('UserID', $UserID)->Get()->FirstRow();
         if ($Data)
            $this->_UpdateCampaignMonitor($Data->Email, $Data->Name, $Data->Newsletter);
      }
   }
   
   public function Base_GetAppSettingsMenuItems_Handler($Sender) {
      $Menu = $Sender->EventArguments['SideMenu'];
      $Menu->AddLink('Site Settings', 'Update Checkers', 'updates/', 'Vanilla.Forums.Manage');
      $Menu->AddLink('Site Settings', 'Download Summary', 'vstats', 'Vanilla.Forums.Manage');
   }
   
   public function Setup() {
      $Database = Gdn::Database();
      $Config = Gdn::Factory(Gdn::AliasConfig);
      $Drop = FALSE;  // Gdn::Config('VFOrg.Version') === FALSE ? TRUE : FALSE;
      $Explicit = TRUE;
      $Validation = new Gdn_Validation(); // This is going to be needed by structure.php to validate permission names
      include(PATH_APPLICATIONS . DS . 'vforg' . DS . 'settings' . DS . 'structure.php');
      
      $ApplicationInfo = array();
      include(CombinePaths(array(PATH_APPLICATIONS . DS . 'vforg' . DS . 'settings' . DS . 'about.php')));
      $Version = ArrayValue('Version', ArrayValue('VFOrg', $ApplicationInfo, array()), 'Undefined');
      SaveToConfig('VFOrg.Version', $Version);
   }
}