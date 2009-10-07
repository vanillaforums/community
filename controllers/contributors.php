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
class ContributorsController extends VFOrgController {
   
   public $Uses = array('Form', 'Database');
   
   public function Initialize() {
      parent::Initialize();
   }
   
   public function Index() {
		$Session = Gdn::Session();
		if (!$Session->IsValid()) {
			$this->View = 'signin';
		} else {
			if ($this->Form->AuthenticatedPostBack() && $this->Form->GetFormValue('Agree', '') == '1') {
				$this->Database->Structure()->Table('User')
				   ->Column('DateContributorAgreement', 'datetime', '', TRUE)
					->Set(FALSE, FALSE);
				$this->Database->SQL()->Update('User')->Set('DateContributorAgreement', Format::ToDateTime(), TRUE, FALSE)->Where('UserID', $Session->UserID)->Put();
				$this->View = 'done';
			}
		}
      $this->Render();
   }   

   public function Signed() {
		$this->UserData = $this->Database->SQL()->Select()->From('User')->Where('DateContributorAgreement <>', '')->Get();
      $this->Render();
   }   
}