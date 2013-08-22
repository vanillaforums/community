<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2008, 2009 Vanilla Forums Inc.
This file is part of Garden.
Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
Contact Vanilla Forums Inc. at support [at] vanillaforums [dot] com
*/

/**
 * MessagesController handles displaying lists of conversations and conversation messages.
 */
class GetController extends AddonsController {
   
   public $Uses = array('Form', 'Database', 'AddonModel');
   
   public function Index($ID = '', $ServeFile = '0') {
      $this->AddJsFile('js/library/jquery.js');

      // Define the item being downloaded
      if (strtolower($ID) == 'vanilla')
         $ID = 'vanilla-core';

      $UrlFilename = Gdn::Request()->Filename();
      $PathInfo = pathinfo($UrlFilename);
      
      $Ext = GetValue('extension', $PathInfo);
      if ($Ext == 'zip') {
         $ServeFile = '1';
         $ID = $Ext = GetValue('filename', $PathInfo);
      }

      // Find the requested addon
      $this->Addon = $this->AddonModel->GetSlug($ID, TRUE);
      $this->SetData('Addon', $this->Addon);
      
      if (!is_array($this->Addon) || !GetValue('File', $this->Addon)) {
         $this->Addon = array(
            'Name' => 'Not Found',
            'Version' => 'undefined',
            'File' => '');
      } else {
         $AddonID = $this->Addon['AddonID'];
         if ($ServeFile != '1')
            $this->AddJsFile('get.js');
         
         if ($ServeFile == '1') {
            // Record this download
            $this->Database->SQL()->Insert('Download', array(
               'AddonID' => $AddonID,
               'DateInserted' => Gdn_Format::ToDateTime(),
               'RemoteIp' => @$_SERVER['REMOTE_ADDR']
            ));
            $this->AddonModel->SetProperty($AddonID, 'CountDownloads', $this->Addon['CountDownloads'] + 1);

            if (GetValue('Slug', $this->Addon))
               $Filename = $this->Addon['Slug'];
            else
               $Filename = "{$this->Addon['Name']}-{$this->Addon['Version']}";

            $Filename = Gdn_Format::Url($Filename).'.zip';
            
            
            $File = $this->Addon['File'];
            $Url = Gdn_Upload::Url($File);
            Gdn_FileSystem::ServeFile($Url, $Filename);
         }
      }
      
      $this->AddModule('AddonHelpModule');      
      $this->Render();
   }
}