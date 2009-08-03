<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2008, 2009 Mark O'Sullivan
This file is part of Garden.
Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
Contact Mark O'Sullivan at mark [at] lussumo [dot] com
*/

/**
 * MessagesController handles displaying lists of conversations and conversation messages.
 */
class GetController extends VanillaForumsOrgController {
   
   public $Uses = array('Form', 'Database', 'Gdn_AddonModel');
	
   public function Index($AddonID = '', $ServeFile = '0') {
      if ($this->Head) {
         $this->Head->AddScript('js/library/jquery.js');
			if ($ServeFile != '1')
				$this->Head->AddScript('applications/vanillaforumsorg/js/get.js');
      }

		// Define the item being downloaded
		if (strtolower($AddonID) == 'vanilla')
			$AddonID = 465;
			
		// Find the requested addon
		$this->Addon = $this->AddonModel->GetID($AddonID);
		if (!is_object($this->Addon)) {
			$this->Addon = new stdClass();
			$this->Addon->Name = 'Not Found';
			$this->Addon->Version = 'undefined';
			$this->Addon->File = '';
		} else {
			if ($ServeFile == '1') {
				// Record this download
				$this->Database->SQL()->Insert('Download', array(
					'AddonID' => $this->Addon->AddonID,
					'DateInserted' => Format::ToDateTime(),
					'RemoteIp' => @$_SERVER['REMOTE_ADDR']
				));
				$this->AddonModel->SetProperty($this->Addon->AddonID, 'CountDownloads', $this->Addon->CountDownloads + 1);
				Gdn_FileSystem::ServeFile('uploads/'.$this->Addon->File, Format::Url($this->Addon->Name.'-'.$this->Addon->Version));
			}
		}
		
      $this->Render();
   }
}